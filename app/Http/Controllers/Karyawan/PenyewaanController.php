<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Penyewaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenyewaanController extends Controller
{
    /**
     * Display a listing of penyewaan for karyawan's location
     */
    public function index(Request $request)
    {
        $karyawan = Auth::user();
        $lokasiRentalId = $karyawan->lokasi_rental_id;

        if (!$lokasiRentalId) {
            return redirect()->route('karyawan.dashboard')->with('error', 'You are not assigned to any location!');
        }

        $query = Penyewaan::where('lokasi_rental_id', $lokasiRentalId)
            ->with('user', 'unitMotor.motor', 'lokasiRental');

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $penyewaans = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('karyawan.penyewaan.index', compact('penyewaans'));
    }

    /**
     * Display the specified penyewaan
     */
    public function show(Penyewaan $penyewaan)
    {
        $karyawan = Auth::user();

        // Check if penyewaan belongs to karyawan's location
        if ($penyewaan->lokasi_rental_id !== $karyawan->lokasi_rental_id) {
            abort(403, 'Unauthorized access');
        }

        $penyewaan->load('user', 'unitMotor.motor', 'lokasiRental');
        return view('karyawan.penyewaan.show', compact('penyewaan'));
    }

    /**
     * Update penyewaan status to Accept
     */
    public function accept(Penyewaan $penyewaan)
    {
        $karyawan = Auth::user();

        // Check if penyewaan belongs to karyawan's location
        if ($penyewaan->lokasi_rental_id !== $karyawan->lokasi_rental_id) {
            abort(403, 'Unauthorized access');
        }

        if ($penyewaan->status !== 'Pending') {
            return back()->with('error', 'Only pending penyewaan can be accepted!');
        }

        // Update penyewaan status
        $penyewaan->update(['status' => 'Accept']);

        // Update unit motor status to disewa
        $penyewaan->unitMotor->update(['status' => 'disewa']);

        return back()->with('success', 'Penyewaan accepted successfully!');
    }

    /**
     * Update penyewaan status to Tolak
     */
    public function reject(Penyewaan $penyewaan)
    {
        $karyawan = Auth::user();

        // Check if penyewaan belongs to karyawan's location
        if ($penyewaan->lokasi_rental_id !== $karyawan->lokasi_rental_id) {
            abort(403, 'Unauthorized access');
        }

        if ($penyewaan->status !== 'Pending') {
            return back()->with('error', 'Only pending penyewaan can be rejected!');
        }

        // Update penyewaan status
        $penyewaan->update(['status' => 'Tolak']);

        return back()->with('success', 'Penyewaan rejected successfully!');
    }

    /**
     * Complete penyewaan (return unit)
     */
    public function complete(Penyewaan $penyewaan)
    {
        $karyawan = Auth::user();

        // Check if penyewaan belongs to karyawan's location
        if ($penyewaan->lokasi_rental_id !== $karyawan->lokasi_rental_id) {
            abort(403, 'Unauthorized access');
        }

        if ($penyewaan->status !== 'Accept') {
            return back()->with('error', 'Only accepted penyewaan can be completed!');
        }

        // Update unit motor status back to tersedia
        $penyewaan->unitMotor->update(['status' => 'tersedia']);

        return back()->with('success', 'Penyewaan completed! Unit is now available.');
    }
}