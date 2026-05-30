<?php

namespace App\Jobs;

use App\Models\Kuis;
use App\Models\Materi;
use App\Models\SoalKuis;
use App\Services\AiService;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProsesMateriAI implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    public $materi;

    /**
     * Create a new job instance.
     */
    public function __construct(Materi $materi)
    {
        $this->materi = $materi;
    }

    /**
     * Execute the job.
     */
    public function handle(AiService $aiService): void
    {
        \Log::info(
            'ProsesMateriAI mulai untuk materi ID: '
            . $this->materi->id
        );

        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA TERBARU
        |--------------------------------------------------------------------------
        */

        $materi = Materi::find(
            $this->materi->id
        );

        if (!$materi) {

            \Log::error(
                'Materi tidak ditemukan'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | EKSTRAK TEKS PDF
        |--------------------------------------------------------------------------
        */

        $teks = $aiService->ekstrakTeks(
            $materi->file_path
        );

        if (empty(trim($teks))) {

            \Log::warning(
                'Teks PDF kosong untuk materi ID: '
                . $materi->id
            );

            $materi->update([
                'ringkasan' =>
                    'Gagal membaca isi file PDF.',
            ]);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | GENERATE RINGKASAN + KUIS SEKALIGUS
        |--------------------------------------------------------------------------
        */

        $hasilAI = $aiService
            ->generateMateriDanKuis($teks);

        if (!$hasilAI) {

            \Log::error(
                'AI gagal membuat ringkasan dan kuis.'
            );

            $materi->update([
                'ringkasan' => 'Gagal membaca isi file. Pastikan file tidak kosong atau terenkripsi.',
            ]);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA HASIL AI
        |--------------------------------------------------------------------------
        */

        $ringkasan =
            $hasilAI['ringkasan']
            ?? 'Ringkasan gagal dibuat.';

        $soals =
            $hasilAI['kuis']
            ?? [];

        /*
        |--------------------------------------------------------------------------
        | SIMPAN RINGKASAN
        |--------------------------------------------------------------------------
        */

        $materi->update([

            'teks_ekstrak' => $teks,

            'ringkasan' => $ringkasan,
        ]);

        \Log::info(
            'Ringkasan berhasil dibuat'
        );

        /*
        |--------------------------------------------------------------------------
        | VALIDASI SOAL
        |--------------------------------------------------------------------------
        */

        if (
            empty($soals)
            || !is_array($soals)
        ) {

            \Log::warning(
                'Soal kuis gagal dibuat.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | HAPUS KUIS LAMA
        |--------------------------------------------------------------------------
        */

        foreach ($materi->kuis as $oldKuis) {

            $oldKuis
                ->soalKuis()
                ->delete();

            $oldKuis->delete();
        }

        /*
        |--------------------------------------------------------------------------
        | BUAT DATA KUIS
        |--------------------------------------------------------------------------
        */

        $kuis = Kuis::create([

            'materi_id' =>
                $materi->id,

            'user_id' =>
                $materi->user_id,

            'judul_kuis' =>
                'Kuis: '
                . $materi->judul,
        ]);

        /*
        |--------------------------------------------------------------------------
        | SIMPAN SOAL
        |--------------------------------------------------------------------------
        */

        foreach ($soals as $soal) {

            SoalKuis::create([

                'kuis_id' =>
                    $kuis->id,

                'pertanyaan' =>
                    $soal['pertanyaan']
                    ?? '',

                'opsi_a' =>
                    $soal['opsi_a']
                    ?? '',

                'opsi_b' =>
                    $soal['opsi_b']
                    ?? '',

                'opsi_c' =>
                    $soal['opsi_c']
                    ?? '',

                'opsi_d' =>
                    $soal['opsi_d']
                    ?? '',

                'jawaban_benar' =>
                    strtolower(
                        $soal['jawaban_benar']
                        ?? 'a'
                    ),

                'penjelasan' =>
                    $soal['penjelasan']
                    ?? $soal['penjelasan_ai']
                    ?? '',
            ]);
        }

        \Log::info(
            'Kuis berhasil dibuat untuk materi ID: '
            . $materi->id
        );

        \Log::info(
            'ProsesMateriAI selesai.'
        );
    }
}