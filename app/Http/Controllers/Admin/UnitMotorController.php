<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnitMotor;
use App\Models\Motor;
use App\Models\LokasiRental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UnitMotorController extends Controller
{
    /**
     * Display a listing of unit motor
     */
    public function index()
    {
        $unitMotors = UnitMotor::with('motor', 'lokasiRental')->paginate(10);
        return view('admin.unit-motor.index', compact('unitMotors'));
    }

    /**
     * Show the form for creating a new unit motor
     */
    public function create()
    {
        $motors = Motor::all();
        $lokasiRentals = LokasiRental::all();
        return view('admin.unit-motor.create', compact('motors', 'lokasiRentals'));
    }

    /**
     * Store a newly created unit motor
     */
    public function store(Request $request)
    {
        $request->validate([
            'motor_id' => 'required|exists:motor,id',
            'lokasi_rental_id' => 'required|exists:lokasi_rental,id',
            'plat_nomor' => 'required|string|max:20|unique:unit_motor',
            'status' => 'required|in:tersedia,disewa,maintenance',
            'foto_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'motor_id' => $request->motor_id,
            'lokasi_rental_id' => $request->lokasi_rental_id,
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

        return redirect()->route('admin.unit-motor.index')->with('success', 'Unit motor created successfully!');
    }

    /**
     * Display the specified unit motor
     */
    public function show(UnitMotor $unitMotor)
    {
        $unitMotor->load('motor', 'lokasiRental', 'penyewaan.user');
        return view('admin.unit-motor.show', compact('unitMotor'));
    }

    /**
     * Show the form for editing the specified unit motor
     */
    public function edit(UnitMotor $unitMotor)
    {
        $motors = Motor::all();
        $lokasiRentals = LokasiRental::all();
        return view('admin.unit-motor.edit', compact('unitMotor', 'motors', 'lokasiRentals'));
    }

    /**
     * Update the specified unit motor
     */
    public function update(Request $request, UnitMotor $unitMotor)
    {
        $request->validate([
            'motor_id' => 'required|exists:motor,id',
            'lokasi_rental_id' => 'required|exists:lokasi_rental,id',
            'plat_nomor' => 'required|string|max:20|unique:unit_motor,plat_nomor,' . $unitMotor->id,
            'status' => 'required|in:tersedia,disewa,maintenance',
            'foto_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'motor_id' => $request->motor_id,
            'lokasi_rental_id' => $request->lokasi_rental_id,
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

        return redirect()->route('admin.unit-motor.index')->with('success', 'Unit motor updated successfully!');
    }

    /**
     * Remove the specified unit motor
     */
    public function destroy(UnitMotor $unitMotor)
    {
        // Delete photo if exists
        if ($unitMotor->foto_path && Storage::disk('local')->exists($unitMotor->foto_path)) {
            Storage::disk('local')->delete($unitMotor->foto_path);
        }

        $unitMotor->delete();

        return redirect()->route('admin.unit-motor.index')->with('success', 'Unit motor deleted successfully!');
    }
}