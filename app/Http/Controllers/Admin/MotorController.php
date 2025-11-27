<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Motor;
use Illuminate\Http\Request;

class MotorController extends Controller
{
    /**
     * Display a listing of motor
     */
    public function index()
    {
        $motors = Motor::withCount('unitMotor')->paginate(10);
        return view('admin.motor.index', compact('motors'));
    }

    /**
     * Show the form for creating a new motor
     */
    public function create()
    {
        return view('admin.motor.create');
    }

    /**
     * Store a newly created motor
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_motor' => 'required|string|max:255',
            'merk' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'cc' => 'required|integer|min:50|max:2000',
            'deskripsi' => 'nullable|string',
        ]);

        Motor::create($request->all());

        return redirect()->route('admin.motor.index')->with('success', 'Motor created successfully!');
    }

    /**
     * Display the specified motor
     */
    public function show(Motor $motor)
    {
        $motor->load('unitMotor.lokasiRental');
        return view('admin.motor.show', compact('motor'));
    }

    /**
     * Show the form for editing the specified motor
     */
    public function edit(Motor $motor)
    {
        return view('admin.motor.edit', compact('motor'));
    }

    /**
     * Update the specified motor
     */
    public function update(Request $request, Motor $motor)
    {
        $request->validate([
            'nama_motor' => 'required|string|max:255',
            'merk' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'cc' => 'required|integer|min:50|max:2000',
            'deskripsi' => 'nullable|string',
        ]);

        $motor->update($request->all());

        return redirect()->route('admin.motor.index')->with('success', 'Motor updated successfully!');
    }

    /**
     * Remove the specified motor
     */
    public function destroy(Motor $motor)
    {
        $motor->delete();

        return redirect()->route('admin.motor.index')->with('success', 'Motor deleted successfully!');
    }
}