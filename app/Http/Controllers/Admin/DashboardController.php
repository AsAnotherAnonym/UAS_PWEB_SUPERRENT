<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LokasiRental;
use App\Models\Motor;
use App\Models\UnitMotor;
use App\Models\Penyewaan;
use App\Models\Review;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard with statistics
     */
    public function index(Request $request)
    {
        // Default filter: 30 days
        $filter = $request->get('filter', '30');
        
        // Calculate date range
        $startDate = $this->getStartDate($filter);
        $endDate = Carbon::now();

        // Total counts
        $totalUsers = User::where('role', 'user')->count();
        $totalKaryawan = User::where('role', 'karyawan')->count();
        $totalLokasi = LokasiRental::count();
        $totalMotor = Motor::count();
        $totalUnit = UnitMotor::count();
        $totalReviews = Review::count();

        // Penyewaan statistics
        $totalPenyewaan = Penyewaan::whereBetween('created_at', [$startDate, $endDate])->count();
        $pendingPenyewaan = Penyewaan::where('status', 'Pending')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $acceptedPenyewaan = Penyewaan::where('status', 'Accept')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $rejectedPenyewaan = Penyewaan::where('status', 'Tolak')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Unit status counts
        $unitTersedia = UnitMotor::where('status', 'tersedia')->count();
        $unitDisewa = UnitMotor::where('status', 'disewa')->count();
        $unitMaintenance = UnitMotor::where('status', 'maintenance')->count();

        // Chart data for penyewaan over time
        $chartData = $this->getChartData($startDate, $endDate, $filter);

        // Top rented motors
        $topMotors = Penyewaan::with('unitMotor.motor')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'Accept')
            ->get()
            ->groupBy('unitMotor.motor.nama_motor')
            ->map(function ($group) {
                return [
                    'nama_motor' => $group->first()->unitMotor->motor->nama_motor,
                    'count' => $group->count()
                ];
            })
            ->sortByDesc('count')
            ->take(5)
            ->values();

        // Top locations
        $topLokasi = Penyewaan::with('lokasiRental')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'Accept')
            ->get()
            ->groupBy('lokasiRental.nama_cabang')
            ->map(function ($group) {
                return [
                    'nama_cabang' => $group->first()->lokasiRental->nama_cabang,
                    'count' => $group->count()
                ];
            })
            ->sortByDesc('count')
            ->take(5)
            ->values();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalKaryawan',
            'totalLokasi',
            'totalMotor',
            'totalUnit',
            'totalReviews',
            'totalPenyewaan',
            'pendingPenyewaan',
            'acceptedPenyewaan',
            'rejectedPenyewaan',
            'unitTersedia',
            'unitDisewa',
            'unitMaintenance',
            'chartData',
            'topMotors',
            'topLokasi',
            'filter'
        ));
    }

    /**
     * Get start date based on filter
     */
    private function getStartDate($filter)
    {
        switch ($filter) {
            case '1':
                return Carbon::now()->subDay();
            case '7':
                return Carbon::now()->subDays(7);
            case '30':
                return Carbon::now()->subDays(30);
            case '365':
                return Carbon::now()->subYear();
            default:
                return Carbon::now()->subDays(30);
        }
    }

    /**
     * Get chart data for penyewaan over time
     */
    private function getChartData($startDate, $endDate, $filter)
    {
        $penyewaans = Penyewaan::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'Accept')
            ->get();

        $format = $filter == '1' ? 'H:00' : ($filter == '365' ? 'M Y' : 'd M');

        $grouped = $penyewaans->groupBy(function ($item) use ($format) {
            return $item->created_at->format($format);
        });

        $labels = [];
        $data = [];

        foreach ($grouped as $date => $items) {
            $labels[] = $date;
            $data[] = $items->count();
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
}