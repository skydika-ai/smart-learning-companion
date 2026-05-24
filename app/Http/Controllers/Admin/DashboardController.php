<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Materi;
use App\Models\Kuis;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUser = User::where('role', 'user')->count();
        $totalMateri = Materi::count();
        $totalKuis = Kuis::count();

        return view('admin.dashboard', compact('totalUser', 'totalMateri', 'totalKuis'));
    }
}
