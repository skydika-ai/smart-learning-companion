<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// use App\Services\AiService; // Akan diisi oleh Dimas

class MateriController extends Controller
{
    public function index()
    {
        $materis = Materi::where('user_id', auth()->id())->get();
        return view('materi.index', compact('materis'));
    }

    public function create()
    {
        return view('materi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,txt|max:5120',
        ]);

        $filePath = $request->file('file')->store('materis', 'public');

        // Placeholder untuk logic AI (Dimas)
        $teksEkstrak = "Teks hasil ekstraksi dummy"; 
        
        // $ringkasan = app(AiService::class)->buatRingkasan($teksEkstrak);
        $ringkasan = "Ringkasan dummy dari AI";

        $materi = Materi::create([
            'user_id' => auth()->id(),
            'judul' => $request->judul,
            'file_path' => $filePath,
            'teks_ekstrak' => $teksEkstrak,
            'ringkasan' => $ringkasan,
        ]);

        return redirect()->route('materi.show', $materi)->with('success', 'Materi berhasil diupload.');
    }

    public function show(Materi $materi)
    {
        if ($materi->user_id !== auth()->id()) abort(403);
        
        return view('materi.show', compact('materi'));
    }

    public function destroy(Materi $materi)
    {
        if ($materi->user_id !== auth()->id()) abort(403);

        if (Storage::disk('public')->exists($materi->file_path)) {
            Storage::disk('public')->delete($materi->file_path);
        }
        $materi->delete();

        return redirect()->route('materi.index')->with('success', 'Materi berhasil dihapus.');
    }
}
