<?php

namespace App\Http\Controllers;

use App\Models\Materi;

class RingkasanController extends Controller
{
    public function index()
    {
        $materis = Materi::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('ringkasan.index', compact('materis'));
    }
}