<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Penyewaan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display user dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // Total penyewaan yang pernah dilakukan
        $totalPenyewaan = Penyewaan::where('user_id', $user->id)
            ->where('status', 'Accept')
            ->count();

        // Total hari menyewa
        $totalHariSewa = Penyewaan::where('user_id', $user->id)
            ->where('status', 'Accept')
            ->get()
            ->sum(function ($penyewaan) {
                return $penyewaan->getTotalHari();
            });

        // Cek apakah sedang menyewa (penyewaan aktif)
        $activePenyewaan = Penyewaan::where('user_id', $user->id)
            ->where('status', 'Accept')
            ->whereDate('tanggal_mulai', '<=', Carbon::today())
            ->whereDate('tanggal_selesai', '>=', Carbon::today())
            ->with('unitMotor.motor', 'lokasiRental')
            ->first();

        // Riwayat penyewaan terbaru
        $recentPenyewaan = Penyewaan::where('user_id', $user->id)
            ->with('unitMotor.motor', 'lokasiRental')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('user.dashboard', compact(
            'totalPenyewaan',
            'totalHariSewa',
            'activePenyewaan',
            'recentPenyewaan'
        ));
    }
}