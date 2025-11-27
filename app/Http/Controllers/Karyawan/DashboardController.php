<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Penyewaan;
use App\Models\UnitMotor;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display karyawan dashboard
     */
    public function index()
    {
        $karyawan = Auth::user();
        $lokasiRentalId = $karyawan->lokasi_rental_id;

        if (!$lokasiRentalId) {
            return view('karyawan.dashboard')->with('error', 'You are not assigned to any location!');
        }

        // Statistics for karyawan's location only
        $totalUnit = UnitMotor::where('lokasi_rental_id', $lokasiRentalId)->count();
        $unitTersedia = UnitMotor::where('lokasi_rental_id', $lokasiRentalId)->where('status', 'tersedia')->count();
        $unitDisewa = UnitMotor::where('lokasi_rental_id', $lokasiRentalId)->where('status', 'disewa')->count();
        $unitMaintenance = UnitMotor::where('lokasi_rental_id', $lokasiRentalId)->where('status', 'maintenance')->count();

        $totalPenyewaan = Penyewaan::where('lokasi_rental_id', $lokasiRentalId)->count();
        $pendingPenyewaan = Penyewaan::where('lokasi_rental_id', $lokasiRentalId)->where('status', 'Pending')->count();
        $acceptedPenyewaan = Penyewaan::where('lokasi_rental_id', $lokasiRentalId)->where('status', 'Accept')->count();

        // Recent penyewaan
        $recentPenyewaan = Penyewaan::where('lokasi_rental_id', $lokasiRentalId)
            ->with('user', 'unitMotor.motor')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('karyawan.dashboard', compact(
            'totalUnit',
            'unitTersedia',
            'unitDisewa',
            'unitMaintenance',
            'totalPenyewaan',
            'pendingPenyewaan',
            'acceptedPenyewaan',
            'recentPenyewaan'
        ));
    }
}