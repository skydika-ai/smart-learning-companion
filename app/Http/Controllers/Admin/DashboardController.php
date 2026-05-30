<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Materi;
use App\Models\HasilKuis;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats    = $this->getStats();
        $grafik   = $this->getChartData();
        $aktiv    = $this->getActivitiesForBlade();

        return view('admin.dashboard', [
            'totalUser'        => $stats['total_users'],
            'userPct'          => $stats['user_pct'],
            'totalMateri'      => $stats['total_dokumen'],
            'materiPct'        => $stats['dokumen_pct'],
            'totalKuis'        => $stats['total_kuis'],
            'kuisPct'          => $stats['kuis_pct'],
            'aktivitasHariIni' => $stats['today_activity'],
            'aktivitasPct'     => $stats['activity_pct'],
            'recentUsers'      => User::latest()->take(5)->get(),
            'grafikLabels'     => $grafik['labels'],
            'grafikData'       => $grafik['data'],
            'aktivitasTerbaru' => $aktiv,
        ]);
    }

    // ── Endpoint AJAX untuk polling real-time ──
    public function realtimeData()
    {
        return response()->json([
            'stats'       => $this->getStatsForJs(),
            'chartData'   => $this->getChartData(),
            'recentUsers' => $this->getRecentUsers(),
            'activities'  => $this->getActivitiesForJs(),
            'timestamp'   => now()->format('H:i:s'),
        ]);
    }

    // ────────────────────────────────────────────
    // PRIVATE HELPERS
    // ────────────────────────────────────────────

    private function getStats(): array
    {
        $thisMonth    = Carbon::now()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        $totalUserNow   = User::count();
        $totalUserLast  = User::where('created_at', '<', $thisMonth)->count();
        $userPct        = $totalUserLast > 0
            ? round((($totalUserNow - $totalUserLast) / $totalUserLast) * 100)
            : 0;

        $totalMateriNow  = Materi::count();
        $totalMateriLast = Materi::where('created_at', '<', $thisMonth)->count();
        $materiPct       = $totalMateriLast > 0
            ? round((($totalMateriNow - $totalMateriLast) / $totalMateriLast) * 100)
            : 0;

        $totalKuisNow  = HasilKuis::count();
        $totalKuisLast = HasilKuis::where('created_at', '<', $thisMonth)->count();
        $kuisPct       = $totalKuisLast > 0
            ? round((($totalKuisNow - $totalKuisLast) / $totalKuisLast) * 100)
            : 0;

        $aktivitasHariIni = HasilKuis::whereDate('created_at', today())->count()
                          + Materi::whereDate('created_at', today())->count();
        $aktivitasKemarin = HasilKuis::whereDate('created_at', today()->subDay())->count()
                          + Materi::whereDate('created_at', today()->subDay())->count();
        $aktivitasPct     = $aktivitasKemarin > 0
            ? round((($aktivitasHariIni - $aktivitasKemarin) / $aktivitasKemarin) * 100)
            : ($aktivitasHariIni > 0 ? 100 : 0);

        return [
            'total_users'    => $totalUserNow,
            'user_pct'       => $userPct,
            'total_dokumen'  => $totalMateriNow,
            'dokumen_pct'    => $materiPct,
            'total_kuis'     => $totalKuisNow,
            'kuis_pct'       => $kuisPct,
            'today_activity' => $aktivitasHariIni,
            'activity_pct'   => $aktivitasPct,
        ];
    }

    // Format untuk response JSON (key cocok dengan JS di blade)
    private function getStatsForJs(): array
    {
        $s = $this->getStats();
        return [
            'total_users'     => $s['total_users'],
            'user_growth'     => $s['user_pct'],
            'total_dokumen'   => $s['total_dokumen'],
            'dokumen_growth'  => $s['dokumen_pct'],
            'total_kuis'      => $s['total_kuis'],
            'kuis_growth'     => $s['kuis_pct'],
            'today_activity'  => $s['today_activity'],
            'activity_growth' => $s['activity_pct'],
        ];
    }

    private function getChartData(): array
    {
        $labels = [];
        $data   = [];

        for ($i = 6; $i >= 0; $i--) {
            $date     = Carbon::now()->subDays($i);
            $labels[] = $date->translatedFormat('d M');
            $data[]   = User::whereDate('created_at', $date->toDateString())->count();
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function getRecentUsers(): array
    {
        return User::latest()->take(5)->get()
            ->map(fn($u) => [
                'id'        => $u->id,
                'name'      => $u->name,
                'email'     => $u->email,
                'role'      => $u->role,
                'is_active' => (bool) $u->is_active,
            ])
            ->toArray();
    }

    // Untuk blade (return collection of objects, struktur lama tetap jalan)
    private function getActivitiesForBlade()
    {
        $materis = Materi::with('user')->latest()->take(5)->get()->map(fn($m) => (object)[
            'user'   => $m->user,
            'aksi'   => 'mengupload',
            'target' => $m->judul,
            'waktu'  => $m->created_at,
            'icon'   => 'upload',
        ]);

        $kuis = HasilKuis::with(['user', 'kuis'])->latest()->take(5)->get()->map(fn($h) => (object)[
            'user'   => $h->user,
            'aksi'   => 'mengerjakan kuis',
            'target' => $h->kuis->judul_kuis ?? '-',
            'waktu'  => $h->created_at,
            'icon'   => 'kuis',
        ]);

        $newUsers = User::latest()->take(5)->get()->map(fn($u) => (object)[
            'user'   => $u,
            'aksi'   => 'mendaftar sebagai user baru',
            'target' => '',
            'waktu'  => $u->created_at,
            'icon'   => 'user',
        ]);

        return $materis->concat($kuis)->concat($newUsers)
            ->sortByDesc('waktu')->take(8)->values();
    }

    // Untuk JSON response (field cocok dengan updateActivities() di JS)
    private function getActivitiesForJs(): array
    {
        $materis = Materi::with('user')->latest()->take(5)->get()->map(fn($m) => [
            'type'        => 'upload',
            'user_name'   => $m->user->name ?? '-',
            'aksi'        => 'mengupload',
            'target'      => $m->judul,
            'waktu_format' => $m->created_at->format('d M Y, H:i'),
            'waktu_ts'    => $m->created_at->timestamp,
        ]);

        $kuis = HasilKuis::with(['user', 'kuis'])->latest()->take(5)->get()->map(fn($h) => [
            'type'        => 'kuis',
            'user_name'   => $h->user->name ?? '-',
            'aksi'        => 'mengerjakan kuis',
            'target'      => $h->kuis->judul_kuis ?? '-',
            'waktu_format' => $h->created_at->format('d M Y, H:i'),
            'waktu_ts'    => $h->created_at->timestamp,
        ]);

        $newUsers = User::latest()->take(5)->get()->map(fn($u) => [
            'type'        => 'register',
            'user_name'   => $u->name,
            'aksi'        => 'mendaftar sebagai user baru',
            'target'      => '',
            'waktu_format' => $u->created_at->format('d M Y, H:i'),
            'waktu_ts'    => $u->created_at->timestamp,
        ]);

        return $materis->concat($kuis)->concat($newUsers)
            ->sortByDesc('waktu_ts')->take(8)->values()->toArray();
    }
}