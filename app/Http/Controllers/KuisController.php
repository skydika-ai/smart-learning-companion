<?php

namespace App\Http\Controllers;

use App\Models\Kuis;
use App\Models\HasilKuis;
use Illuminate\Http\Request;

class KuisController extends Controller
{
    public function index()
    {
        $kuisList = Kuis::whereHas('materi', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->with(['materi', 'soalKuis', 'hasilKuis' => function ($q) {
                $q->where('user_id', auth()->id())->latest();
            }])
            ->latest()
            ->get();

        return view('kuis.index', compact('kuisList'));
    }

    public function show(Kuis $kuis)
    {
        if ($kuis->user_id !== auth()->id()) {
            abort(403);
        }

        $kuis->load('soalKuis', 'materi');
        $total = $kuis->soalKuis->count();

        return view('kuis.show', compact('kuis', 'total'));
    }

    public function submit(Request $request, Kuis $kuis)
    {
        if ($kuis->user_id !== auth()->id()) {
            abort(403);
        }

        $jawabanUser = collect($request->input('jawaban', []))
            ->mapWithKeys(fn($val, $key) => [(string)$key => strtolower(trim($val))])
            ->toArray();

        $waktuPengerjaan = min((int) $request->input('waktu_pengerjaan', 0), 3600);

        $soals = $kuis->soalKuis;
        $benar = 0;

        foreach ($soals as $soal) {
            $jawabanDiberikan = $jawabanUser[(string)$soal->id] ?? null;
            $jawabanBenar     = strtolower(trim($soal->jawaban_benar));

            if ($jawabanDiberikan && $jawabanDiberikan === $jawabanBenar) {
                $benar++;
            }
        }

        $skor = $soals->count()
            ? round(($benar / $soals->count()) * 100)
            : 0;

        $hasil = HasilKuis::create([
            'kuis_id'          => $kuis->id,
            'user_id'          => auth()->id(),
            'skor'             => $skor,
            'jawaban_user'     => $jawabanUser,
            'waktu_pengerjaan' => $waktuPengerjaan,
        ]);

        return redirect()->route('kuis.result', [
            'kuis'  => $kuis->id,
            'hasil' => $hasil->id,
        ]);
    }

    public function result(Kuis $kuis, HasilKuis $hasil)
    {
        if ($hasil->user_id !== auth()->id()) {
            abort(403);
        }

        $kuis->load('soalKuis', 'materi');

        return view('kuis.result', compact('kuis', 'hasil'));
    }
}