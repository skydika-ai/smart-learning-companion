<?php

namespace App\Services;

use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService
{
    private string $apiKey;
    private string $apiUrl = 'https://api.groq.com/openai/v1/chat/completions';
    private string $model;
    public function __construct()
    {
        $this->apiKey = config('services.groq.key', '');
        $this->model = config('services.groq.model', 'llama-3.3-70b-versatile');
    }

    /*
    |--------------------------------------------------------------------------
    | EKSTRAK TEKS FILE
    |--------------------------------------------------------------------------
    */

    public function ekstrakTeks(string $filePath): string
    {
        try {
            $fullPath = storage_path('app/public/' . $filePath);

            if (!file_exists($fullPath)) return '';

            $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
            $text = '';

            if ($extension === 'pdf') {
                $parser = new Parser();
                $pdf    = $parser->parseFile($fullPath);
                $text   = $pdf->getText();
            } elseif ($extension === 'docx') {
                $phpWord = \PhpOffice\PhpWord\IOFactory::load($fullPath);
                foreach ($phpWord->getSections() as $section) {
                    $text .= $this->ekstrakElemenPhpWord($section) . "\n";
                }
            } elseif ($extension === 'txt') {
                $text = file_get_contents($fullPath);
            }

            if (empty(trim($text))) return '';

            $text = preg_replace('/\r\n|\r/', "\n", $text);
            $text = preg_replace('/[ \t]+/', ' ', $text);
            $text = preg_replace('/\n{3,}/', "\n\n", $text);

            return trim($text);

        } catch (\Exception $e) {
            Log::error('Gagal ekstrak file: ' . $e->getMessage());
            return '';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER REKURSIF EKSTRAK ELEMEN PHPWORD
    |--------------------------------------------------------------------------
    */

    private function ekstrakElemenPhpWord($container): string
    {
        $text = '';
        foreach ($container->getElements() as $element) {
            if ($element instanceof \PhpOffice\PhpWord\Element\TextRun
                || $element instanceof \PhpOffice\PhpWord\Element\Paragraph) {
                $text .= $this->ekstrakElemenPhpWord($element) . "\n";
            } elseif ($element instanceof \PhpOffice\PhpWord\Element\Text) {
                $text .= $element->getText();
            } elseif ($element instanceof \PhpOffice\PhpWord\Element\Table) {
                foreach ($element->getRows() as $row) {
                    foreach ($row->getCells() as $cell) {
                        $text .= $this->ekstrakElemenPhpWord($cell) . ' ';
                    }
                    $text .= "\n";
                }
            } elseif (method_exists($element, 'getText')) {
                $text .= $element->getText();
            }
        }
        return $text;
    }

    /*
    |--------------------------------------------------------------------------
    | POTONG TEKS (max ~3000 kata agar Groq cepat)
    |--------------------------------------------------------------------------
    */

    private function potongTeks(string $teks, int $maxKata = 3000): string
    {
        $kata = explode(' ', $teks);
        if (count($kata) <= $maxKata) return $teks;
        return implode(' ', array_slice($kata, 0, $maxKata)) . '...';
    }

    /*
    |--------------------------------------------------------------------------
    | GROQ — 1 REQUEST untuk ringkasan + soal sekaligus
    |--------------------------------------------------------------------------
    */

    private function panggilGroq(string $teks): ?array
    {
        if (empty($this->apiKey)) {
            Log::warning('GROQ_API_KEY kosong, fallback ke lokal.');
            return null;
        }

        $teksPendek = $this->potongTeks($teks, 3000);

        $prompt = <<<PROMPT
Kamu adalah asisten pendidikan. Berdasarkan materi berikut, buat:
1. Ringkasan dalam Bahasa Indonesia (4-5 poin penting, tiap poin 1-2 kalimat)
2. Tepat 5 soal pilihan ganda dalam Bahasa Indonesia (tidak boleh kurang, tidak boleh lebih dari 5)

Format respons HARUS berupa JSON valid seperti ini (tanpa teks lain):
{
  "ringkasan": "Poin 1: ...\n\nPoin 2: ...\n\nPoin 3: ...\n\nPoin 4: ...\n\nPoin 5: ...",
  "soal": [
    {
      "pertanyaan": "...",
      "opsi_a": "...",
      "opsi_b": "...",
      "opsi_c": "...",
      "opsi_d": "...",
      "jawaban_benar": "a",
      "penjelasan": "..."
    }
  ]
}

Aturan soal WAJIB diikuti:
- Buat soal yang TIDAK TERLALU MUDAH — hindari soal yang jawabannya sudah jelas dari pertanyaan
- Jawaban benar harus bervariasi: gunakan kombinasi a, b, c, d secara acak — JANGAN semua 'a'
- Semua opsi (a, b, c, d) harus terdengar masuk akal dan plausibel — bukan jawaban yang jelas salah
- Opsi yang salah harus menjebak — buat distraktor yang mirip dengan jawaban benar
- Hindari opsi seperti "Semua jawaban di atas benar" atau "Tidak ada yang benar"
- Hindari pertanyaan definisi langsung — buat soal aplikasi/analisis yang lebih menantang
- Variasikan tipe soal: sebab-akibat, perbandingan, aplikasi konsep, contoh kasus
- jawaban_benar diisi huruf kecil: a, b, c, atau d

Materi:
{$teksPendek}
PROMPT;

        try {
            $response = Http::withToken($this->apiKey)
                ->withoutVerifying()
                ->timeout(60)
                ->post($this->apiUrl, [
                    'model'       => $this->model,
                    'messages'    => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'temperature' => 0.4,
                    'max_tokens'  => 2048,
                ]);

            if (!$response->successful()) {
                Log::error('Groq API error: ' . $response->body());
                return null;
            }

            $content = $response->json('choices.0.message.content', '');

            // Bersihkan jika ada markdown code block
            $content = preg_replace('/```json|```/i', '', $content);
            $content = trim($content);

            // Ambil hanya bagian JSON (dari { pertama sampai } terakhir)
            $start = strpos($content, '{');
            $end   = strrpos($content, '}');
            if ($start !== false && $end !== false) {
                $content = substr($content, $start, $end - $start + 1);
            }

            // Fix newline literal di dalam JSON string value → spasi
            // Groq kadang taruh newline di dalam value string sehingga json_decode gagal
            $content = preg_replace('/(?<!\\\\)\n(?=[^"]*"(?:[^"\\\\]|\\\\.)*"[^"]*(?:"[^"\\\\]*(?:\\\\.[^"\\\\]*)*"[^"]*)*$)/', ' ', $content);

            // Cara lebih aman: encode dulu per-baris lalu gabung
            $lines = explode("\n", $content);
            $fixed = '';
            $inString = false;
            foreach ($lines as $line) {
                if ($inString) {
                    $fixed .= ' ' . trim($line);
                } else {
                    $fixed .= $line;
                }
                // hitung apakah kita di dalam string (toggle per kutip tidak escaped)
                $count = preg_match_all('/(?<!\\\\)"/', $line);
                if ($count % 2 !== 0) $inString = !$inString;
            }
            $content = $fixed;

            $data = json_decode($content, true);
            if (json_last_error() !== JSON_ERROR_NONE || !isset($data['ringkasan'], $data['soal'])) {
                Log::error('Groq response JSON tidak valid: ' . $content);
                return null;
            }

            return $data;

        } catch (\Exception $e) {
            Log::error('Groq request gagal: ' . $e->getMessage());
            return null;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE MATERI + KUIS (entry point utama)
    |--------------------------------------------------------------------------
    */

    public function generateMateriDanKuis(string $teks): array
    {
        // Coba Groq dulu
        $groqResult = $this->panggilGroq($teks);

        if ($groqResult) {
            $soal = array_slice($groqResult['soal'], 0, 5);

            if (count($soal) < 5) {
                $tambahan = $this->generateSoalLokal($teks, 5 - count($soal));
                $soal = array_merge($soal, $tambahan);
            }

            return [
                'ringkasan' => $groqResult['ringkasan'],
                'kuis'      => $soal,
            ];
        }

        // Fallback ke lokal jika Groq gagal
        Log::info('Menggunakan fallback lokal.');
        return [
            'ringkasan' => $this->buatRingkasanLokal($teks),
            'kuis'      => $this->generateSoalLokal($teks, 5),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | PUBLIC WRAPPER (agar kompatibel dengan kode lama)
    |--------------------------------------------------------------------------
    */

    public function buatRingkasan(string $teks): string
    {
        $result = $this->panggilGroq($teks);
        return $result ? $result['ringkasan'] : $this->buatRingkasanLokal($teks);
    }

    public function buatSoalKuis(string $teks, int $jumlah = 5): array
    {
        $result = $this->panggilGroq($teks);
        return $result ? $result['soal'] : $this->generateSoalLokal($teks, $jumlah);
    }

    /*
    |--------------------------------------------------------------------------
    | FALLBACK LOKAL — Ringkasan
    |--------------------------------------------------------------------------
    */

    private function buatRingkasanLokal(string $teks): string
    {
        if (empty(trim($teks))) return 'Ringkasan gagal dibuat.';

        $teks = preg_replace('/\r\n|\r/', "\n", $teks);
        $teks = preg_replace('/\s+/', ' ', $teks);

        $patterns = [
            '/\b\d+(\.\d+)+\b/', '/MODUL\s+[IVX0-9]+/i', '/BAB\s+[IVX0-9]+/i',
            '/HALAMAN\s+\d+/i', '/\b\d+\b/', '/DAFTAR ISI/i', '/ALAT DAN BAHAN/i',
        ];
        $teks = preg_replace($patterns, '', $teks);

        $kalimat = preg_split('/(?<=[.!?])\s+/', $teks);
        $kalimat = array_values(array_filter($kalimat, fn($k) =>
            strlen(trim($k)) >= 80 && !str_contains(strtolower($k), 'copyright')
        ));

        $hasil = '';
        foreach (array_slice($kalimat, 0, 6) as $item) {
            $hasil .= ucfirst(trim(preg_replace('/\s+/', ' ', $item))) . "\n\n";
        }

        return empty(trim($hasil))
            ? 'Materi berhasil diproses namun ringkasan belum dapat dibuat.'
            : trim($hasil);
    }

    /*
    |--------------------------------------------------------------------------
    | FALLBACK LOKAL — Soal
    |--------------------------------------------------------------------------
    */

    private function generateSoalLokal(string $teks, int $jumlah = 5): array
    {
        $bankSoal = [
            [
                'keyword'    => 'Flutter',
                'pertanyaan' => 'Apa keunggulan utama Flutter dalam pengembangan aplikasi modern?',
                'jawaban'    => 'Flutter memungkinkan pengembangan aplikasi lintas platform menggunakan satu basis kode.',
                'opsi'       => [
                    'Flutter hanya dapat digunakan untuk aplikasi Android.',
                    'Flutter digunakan khusus untuk pengembangan database.',
                    'Flutter berfungsi sebagai sistem operasi perangkat mobile.',
                ],
                'penjelasan' => 'Flutter mempermudah pengembangan aplikasi Android, iOS, web, dan desktop menggunakan satu source code.',
            ],
            [
                'keyword'    => 'Dart',
                'pertanyaan' => 'Apa fungsi bahasa pemrograman Dart pada Flutter?',
                'jawaban'    => 'Dart digunakan sebagai bahasa utama dalam pengembangan aplikasi Flutter.',
                'opsi'       => [
                    'Dart digunakan untuk mengelola perangkat keras komputer.',
                    'Dart hanya digunakan untuk pengolahan database.',
                    'Dart berfungsi sebagai sistem keamanan jaringan.',
                ],
                'penjelasan' => 'Dart dipilih karena memiliki performa tinggi dan sintaks modern.',
            ],
            [
                'keyword'    => 'API',
                'pertanyaan' => 'Apa fungsi utama API dalam pengembangan aplikasi?',
                'jawaban'    => 'API digunakan untuk menghubungkan dan bertukar data antar aplikasi.',
                'opsi'       => [
                    'API digunakan untuk mendesain tampilan aplikasi.',
                    'API digunakan untuk mempercepat rendering grafis.',
                    'API berfungsi menghapus seluruh data pengguna.',
                ],
                'penjelasan' => 'API memungkinkan komunikasi antar sistem sehingga aplikasi dapat saling mengirim dan menerima data.',
            ],
            [
                'keyword'    => 'Middleware',
                'pertanyaan' => 'Apa peran Middleware dalam framework Laravel?',
                'jawaban'    => 'Middleware berfungsi memfilter request sebelum diproses controller.',
                'opsi'       => [
                    'Middleware digunakan untuk mendesain tampilan frontend.',
                    'Middleware digunakan untuk mempercepat koneksi internet.',
                    'Middleware digunakan untuk menghapus database aplikasi.',
                ],
                'penjelasan' => 'Middleware bekerja sebagai lapisan perantara untuk autentikasi, validasi, dan pengamanan request.',
            ],
            [
                'keyword'    => 'Authentication',
                'pertanyaan' => 'Apa tujuan utama authentication pada sistem aplikasi?',
                'jawaban'    => 'Authentication digunakan untuk memverifikasi identitas pengguna.',
                'opsi'       => [
                    'Authentication digunakan untuk mengatur tampilan aplikasi.',
                    'Authentication digunakan untuk mempercepat loading halaman.',
                    'Authentication digunakan untuk meningkatkan kualitas gambar.',
                ],
                'penjelasan' => 'Authentication memastikan hanya pengguna yang memiliki hak akses yang dapat masuk ke sistem.',
            ],
            [
                'keyword'    => 'Database',
                'pertanyaan' => 'Apa fungsi utama database dalam aplikasi?',
                'jawaban'    => 'Database digunakan untuk menyimpan dan mengelola data aplikasi.',
                'opsi'       => [
                    'Database digunakan untuk mendesain antarmuka aplikasi.',
                    'Database digunakan untuk mempercepat koneksi internet.',
                    'Database digunakan untuk mengatur warna tampilan aplikasi.',
                ],
                'penjelasan' => 'Database membantu aplikasi menyimpan data secara terstruktur sehingga mudah diakses.',
            ],
        ];

        $hasil = [];
        foreach ($bankSoal as $item) {
            if (stripos($teks, $item['keyword']) !== false) {
                $opsiGabung   = array_merge([$item['jawaban']], $item['opsi']);
                shuffle($opsiGabung);
                $jawabanIndex = array_search($item['jawaban'], $opsiGabung);
                $map          = ['a', 'b', 'c', 'd'];
                $hasil[] = [
                    'pertanyaan'    => $item['pertanyaan'],
                    'opsi_a'        => $opsiGabung[0],
                    'opsi_b'        => $opsiGabung[1],
                    'opsi_c'        => $opsiGabung[2],
                    'opsi_d'        => $opsiGabung[3],
                    'jawaban_benar' => $map[$jawabanIndex],
                    'penjelasan_ai' => $item['penjelasan'],
                ];
            }
        }

        while (count($hasil) < 5) {
            $hasil[] = [
                'pertanyaan'    => 'Apa manfaat memahami konsep dasar pengembangan aplikasi?',
                'opsi_a'        => 'Membantu pengembangan aplikasi menjadi lebih terstruktur.',
                'opsi_b'        => 'Digunakan untuk memperbaiki perangkat keras.',
                'opsi_c'        => 'Digunakan untuk meningkatkan kualitas jaringan.',
                'opsi_d'        => 'Digunakan untuk mengatur warna otomatis aplikasi.',
                'jawaban_benar' => 'a',
                'penjelasan_ai' => 'Pemahaman konsep dasar membantu pengembang membangun aplikasi yang lebih terstruktur.',
            ];
        }

        shuffle($hasil);
        return array_slice($hasil, 0, $jumlah);
    }

    /*
    |--------------------------------------------------------------------------
    | ALIAS (kompatibilitas)
    |--------------------------------------------------------------------------
    */

    public function generateSoal(string $teks, int $jumlah = 5): array
    {
        return $this->buatSoalKuis($teks, $jumlah);
    }
}