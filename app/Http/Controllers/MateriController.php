<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Jobs\ProsesMateriAI;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    public function index()
    {
        $materis = Materi::where(
            'user_id',
            auth()->id()
        )->latest()->get();

        return view(
            'materi.index',
            compact('materis')
        );
    }

    public function create()
    {
        return view('materi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'file'  => 'required|file|mimes:pdf,doc,docx,txt|max:10240',
        ]);

        /*
        |--------------------------------------------------------------------------
        | SIMPAN FILE
        |--------------------------------------------------------------------------
        */

        $filePath = $request
            ->file('file')
            ->store('materis', 'public');

        /*
        |--------------------------------------------------------------------------
        | BUAT MATERI
        |--------------------------------------------------------------------------
        */

        $materi = Materi::create([
            'user_id'   => auth()->id(),
            'judul'     => $request->judul,
            'file_path' => $filePath,
        ]);

        /*
        |--------------------------------------------------------------------------
        | JALANKAN AI BACKGROUND
        |--------------------------------------------------------------------------
        */

        // PENTING:
        // kirim object materi, BUKAN id

        ProsesMateriAI::dispatch($materi);

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('materi.show', $materi)
            ->with(
                'success',
                'Materi berhasil diupload. AI sedang memproses...'
            );
    }

    public function show(Materi $materi)
    {
        if ($materi->user_id !== auth()->id()) {
            abort(403);
        }

        $materi->load('kuis.soalKuis');

        return view(
            'materi.show',
            compact('materi')
        );
    }

    public function destroy(Materi $materi)
    {
        if ($materi->user_id !== auth()->id()) {
            abort(403);
        }

        if (
            Storage::disk('public')
                ->exists($materi->file_path)
        ) {
            Storage::disk('public')
                ->delete($materi->file_path);
        }

        $materi->delete();

        return redirect()
            ->route('materi.index')
            ->with(
                'success',
                'Materi berhasil dihapus.'
            );
    }
}