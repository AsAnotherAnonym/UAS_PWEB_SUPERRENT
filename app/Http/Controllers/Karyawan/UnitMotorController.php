<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\UnitMotor;
use App\Models\Motor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitMotorController extends Controller
{
    /**
     * Display a listing of unit motor for karyawan's location
     */
    public function index()
    {
        $karyawan = Auth::user();
        $lokasiRentalId = $karyawan->lokasi_rental_id;

        if (!$lokasiRentalId) {
            return redirect()->route('karyawan.dashboard')->with('error', 'You are not assigned to any location!');
        }

        $unitMotors = UnitMotor::where('lokasi_rental_id', $lokasiRentalId)
            ->with('motor', 'lokasiRental')
            ->paginate(10);

        return view('karyawan.unit-motor.index', compact('unitMotors'));
    }

    /**
     * Show the form for creating a new unit motor
     */
    public function create()
    {
        $karyawan = Auth::user();
        $lokasiRentalId = $karyawan->lokasi_rental_id;

        if (!$lokasiRentalId) {
            return redirect()->route('karyawan.dashboard')->with('error', 'You are not assigned to any location!');
        }

        $motors = Motor::all();
        return view('karyawan.unit-motor.create', compact('motors'));
    }

    /**
     * Store a newly created unit motor
     */
    public function store(Request $request)
    {
        $karyawan = Auth::user();
        $lokasiRentalId = $karyawan->lokasi_rental_id;

        $request->validate([
            'motor_id' => 'required|exists:motor,id',
            'plat_nomor' => 'required|string|max:20|unique:unit_motor',
            'status' => 'required|in:tersedia,disewa,maintenance',
            'foto_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'motor_id' => $request->motor_id,
            'lokasi_rental_id' => $lokasiRentalId,
            'plat_nomor' => $request->plat_nomor,
            'status' => $request->status,
        ];

        // Handle foto upload
        if ($request->hasFile('foto_path')) {
            $file = $request->file('foto_path');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Store in storage/app/units/
            Storage::disk('local')->putFileAs('units', $file, $filename);

            $data['foto_path'] = 'units/' . $filename;
        }

        UnitMotor::create($data);

        return redirect()->route('karyawan.unit-motor.index')->with('success', 'Unit motor created successfully!');
    }

    /**
     * Display the specified unit motor
     */
    public function show(UnitMotor $unitMotor)
    {
        $karyawan = Auth::user();

        // Check if unit belongs to karyawan's location
        if ($unitMotor->lokasi_rental_id !== $karyawan->lokasi_rental_id) {
            abort(403, 'Unauthorized access');
        }

        $unitMotor->load('motor', 'lokasiRental', 'penyewaan.user');
        return view('karyawan.unit-motor.show', compact('unitMotor'));
    }

    /**
     * Show the form for editing the specified unit motor
     */
    public function edit(UnitMotor $unitMotor)
    {
        $karyawan = Auth::user();

        // Check if unit belongs to karyawan's location
        if ($unitMotor->lokasi_rental_id !== $karyawan->lokasi_rental_id) {
            abort(403, 'Unauthorized access');
        }

        $motors = Motor::all();
        return view('karyawan.unit-motor.edit', compact('unitMotor', 'motors'));
    }

    /**
     * Update the specified unit motor
     */
    public function update(Request $request, UnitMotor $unitMotor)
    {
        $karyawan = Auth::user();

        // Check if unit belongs to karyawan's location
        if ($unitMotor->lokasi_rental_id !== $karyawan->lokasi_rental_id) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'motor_id' => 'required|exists:motor,id',
            'plat_nomor' => 'required|string|max:20|unique:unit_motor,plat_nomor,' . $unitMotor->id,
            'status' => 'required|in:tersedia,disewa,maintenance',
            'foto_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'motor_id' => $request->motor_id,
            'plat_nomor' => $request->plat_nomor,
            'status' => $request->status,
        ];

        // Handle foto upload
        if ($request->hasFile('foto_path')) {
            // Delete old photo if exists
            if ($unitMotor->foto_path && Storage::disk('local')->exists($unitMotor->foto_path)) {
                Storage::disk('local')->delete($unitMotor->foto_path);
            }
        
            $file = $request->file('foto_path');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Store in storage/app/units/
            Storage::disk('local')->putFileAs('units', $file, $filename);

            $data['foto_path'] = 'units/' . $filename;
        }

        $unitMotor->update($data);

        return redirect()->route('karyawan.unit-motor.index')->with('success', 'Unit motor updated successfully!');
    }

    /**
     * Remove the specified unit motor
     */
    public function destroy(UnitMotor $unitMotor)
    {
        $karyawan = Auth::user();

        // Check if unit belongs to karyawan's location
        if ($unitMotor->lokasi_rental_id !== $karyawan->lokasi_rental_id) {
            abort(403, 'Unauthorized access');
        }

        // Delete photo if exists
        if ($unitMotor->foto_path && Storage::disk('local')->exists($unitMotor->foto_path)) {
            Storage::disk('local')->delete($unitMotor->foto_path);
        }

        $unitMotor->delete();

        return redirect()->route('karyawan.unit-motor.index')->with('success', 'Unit motor deleted successfully!');
    }
}