<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penyewaan;
use App\Models\UnitMotor;
use Illuminate\Http\Request;

class PenyewaanController extends Controller
{
    /**
     * Display a listing of penyewaan
     */
    public function index(Request $request)
    {
        $query = Penyewaan::with('user', 'unitMotor.motor', 'lokasiRental');

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $penyewaans = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.penyewaan.index', compact('penyewaans'));
    }

    /**
     * Display the specified penyewaan
     */
    public function show(Penyewaan $penyewaan)
    {
        $penyewaan->load('user', 'unitMotor.motor', 'lokasiRental');
        return view('admin.penyewaan.show', compact('penyewaan'));
    }

    /**
     * Update penyewaan status to Accept
     */
    public function accept(Penyewaan $penyewaan)
    {
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
        if ($penyewaan->status !== 'Accept') {
            return back()->with('error', 'Only accepted penyewaan can be completed!');
        }

        // Update unit motor status back to tersedia
        $penyewaan->unitMotor->update(['status' => 'tersedia']);

        return back()->with('success', 'Penyewaan completed! Unit is now available.');
    }

    /**
     * Remove the specified penyewaan
     */
    public function destroy(Penyewaan $penyewaan)
    {
        // If penyewaan is accepted, return unit to available
        if ($penyewaan->isAccepted()) {
            $penyewaan->unitMotor->update(['status' => 'tersedia']);
        }

        $penyewaan->delete();

        return redirect()->route('admin.penyewaan.index')->with('success', 'Penyewaan deleted successfully!');
    }
}