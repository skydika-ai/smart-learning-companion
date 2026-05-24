<?php

namespace App\Http\Controllers;

use App\Models\Kuis;
use App\Models\HasilKuis;
use Illuminate\Http\Request;

class KuisController extends Controller
{
    public function show(Kuis $kuis)
    {
        if ($kuis->user_id !== auth()->id()) abort(403);
        
        $kuis->load('soalKuis');
        return view('kuis.show', compact('kuis'));
    }

    public function submit(Request $request, Kuis $kuis)
    {
        if ($kuis->user_id !== auth()->id()) abort(403);

        $jawabanUser = $request->input('jawaban', []);
        $soals = $kuis->soalKuis;
        $benar = 0;

        foreach ($soals as $soal) {
            if (isset($jawabanUser[$soal->id]) && $jawabanUser[$soal->id] === $soal->jawaban_benar) {
                $benar++;
            }
        }

        $skor = count($soals) > 0 ? round(($benar / count($soals)) * 100) : 0;

        HasilKuis::create([
            'kuis_id' => $kuis->id,
            'user_id' => auth()->id(),
            'skor' => $skor,
            'jawaban_user' => $jawabanUser,
        ]);

        return redirect()->route('dashboard')->with('success', 'Kuis berhasil diselesaikan dengan skor ' . $skor);
    }
}
