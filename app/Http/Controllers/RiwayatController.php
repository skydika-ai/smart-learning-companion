<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\HasilKuis;

class RiwayatController extends Controller
{
    public function index()
    {
        $materis = Materi::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($m) => [
                'type'    => 'materi',
                'id'      => $m->id,
                'judul'   => $m->judul,
                'tanggal' => $m->created_at,
                'url'     => route('materi.show', $m),
            ]);

        $hasilKuis = HasilKuis::where('user_id', auth()->id())
            ->with(['kuis.materi'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($h) => [
                'type'    => 'kuis',
                'id'      => $h->id,
                'judul'   => $h->kuis->judul_kuis ?? '-',
                'materi'  => $h->kuis->materi->judul ?? '-',
                'skor'    => $h->skor,
                'tanggal' => $h->created_at,
                'url'     => route('kuis.result', [$h->kuis_id, $h->id]),
            ]);

        $semua = $materis->concat($hasilKuis)
            ->sortByDesc('tanggal')
            ->values();

        return view('riwayat.index', compact('semua', 'materis', 'hasilKuis'));
    }

    public function destroyMateri(Materi $materi)
    {
        abort_if($materi->user_id !== auth()->id(), 403);
        $materi->delete();
        return back()->with('success', 'Materi berhasil dihapus dari riwayat.');
    }

    public function destroyKuis(HasilKuis $hasil)
    {
        abort_if($hasil->user_id !== auth()->id(), 403);
        $hasil->delete();
        return back()->with('success', 'Riwayat kuis berhasil dihapus.');
    }
}