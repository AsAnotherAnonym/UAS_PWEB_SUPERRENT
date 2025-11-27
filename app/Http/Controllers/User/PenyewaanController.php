<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Penyewaan;
use App\Models\UnitMotor;
use App\Models\LokasiRental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PenyewaanController extends Controller
{
    /**
     * Display a listing of user's penyewaan
     */
    public function index()
    {
        $user = Auth::user();

        $penyewaans = Penyewaan::where('user_id', $user->id)
            ->with('unitMotor.motor', 'lokasiRental')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.penyewaan.index', compact('penyewaans'));
    }

    /**
     * Show the form for creating a new penyewaan
     */
    public function create(Request $request)
    {
        // Get available units
        $unitMotors = UnitMotor::where('status', 'tersedia')
            ->with('motor', 'lokasiRental')
            ->get();

        // Get all locations
        $lokasiRentals = LokasiRental::all();

        // Pre-select unit or location from query params
        $selectedUnitId = $request->get('unit_id');
        $selectedLokasiId = $request->get('lokasi_id');

        return view('user.penyewaan.create', compact('unitMotors', 'lokasiRentals', 'selectedUnitId', 'selectedLokasiId'));
    }

    /**
     * Store a newly created penyewaan
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'unit_motor_id' => 'required|exists:unit_motor,id',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        // Check if unit is still available
        $unitMotor = UnitMotor::findOrFail($request->unit_motor_id);

        if ($unitMotor->status !== 'tersedia') {
            return back()->with('error', 'Unit motor is not available!')->withInput();
        }

        // Check if user has active rental
        $hasActiveRental = Penyewaan::where('user_id', $user->id)
            ->where('status', 'Accept')
            ->whereDate('tanggal_selesai', '>=', Carbon::today())
            ->exists();

        if ($hasActiveRental) {
            return back()->with('error', 'You already have an active rental!')->withInput();
        }

        Penyewaan::create([
            'user_id' => $user->id,
            'unit_motor_id' => $request->unit_motor_id,
            'lokasi_rental_id' => $unitMotor->lokasi_rental_id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => 'Pending',
        ]);

        return redirect()->route('user.penyewaan.index')->with('success', 'Rental request submitted successfully! Please wait for approval.');
    }

    /**
     * Display the specified penyewaan
     */
    public function show(Penyewaan $penyewaan)
    {
        $user = Auth::user();

        // Check if penyewaan belongs to user
        if ($penyewaan->user_id !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        $penyewaan->load('unitMotor.motor', 'lokasiRental');
        return view('user.penyewaan.show', compact('penyewaan'));
    }

    /**
     * Cancel pending penyewaan
     */
    public function cancel(Penyewaan $penyewaan)
    {
        $user = Auth::user();

        // Check if penyewaan belongs to user
        if ($penyewaan->user_id !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        if ($penyewaan->status !== 'Pending') {
            return back()->with('error', 'Only pending rental can be cancelled!');
        }

        $penyewaan->delete();

        return redirect()->route('user.penyewaan.index')->with('success', 'Rental cancelled successfully!');
    }
}