<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');

        $users = User::where('role', 'user')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalUser    = User::where('role', 'user')->count();
        $totalAktif   = User::where('role', 'user')->where('is_active', true)->count();
        $totalBlokir  = User::where('role', 'user')->where('is_active', false)->count();

        return view('admin.users.index', compact('users', 'search', 'totalUser', 'totalAktif', 'totalBlokir'));
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function toggleActive(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        if (request()->expectsJson()) {
            return response()->json([
                'success'   => true,
                'is_active' => $user->is_active,
            ]);
        }

        return back()->with('success', 'Status user berhasil diubah.');
    }
}