<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materi;
use App\Models\HasilKuis;

class DashboardController extends Controller
{
    public function index()
    {
        $materis = Materi::where('user_id', auth()->id())->get();
        $hasilKuis = HasilKuis::where('user_id', auth()->id())->with('kuis.materi')->get();
        
        return view('dashboard', compact('materis', 'hasilKuis'));
    }
}
