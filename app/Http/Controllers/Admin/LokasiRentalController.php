<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LokasiRental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LokasiRentalController extends Controller
{
    /**
     * Display a listing of lokasi rental
     */
    public function index()
    {
        $lokasiRentals = LokasiRental::withCount('unitMotor')->paginate(10);
        return view('admin.lokasi-rental.index', compact('lokasiRentals'));
    }

    /**
     * Show the form for creating a new lokasi rental
     */
    public function create()
    {
        return view('admin.lokasi-rental.create');
    }

    /**
     * Store a newly created lokasi rental
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_cabang' => 'required|string|max:255',
            'alamat' => 'required|string',
            'deskripsi' => 'nullable|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'foto_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'nama_cabang' => $request->nama_cabang,
            'alamat' => $request->alamat,
            'deskripsi' => $request->deskripsi,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];

        // Handle foto upload
        if ($request->hasFile('foto_path')) {
            $file = $request->file('foto_path');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Store in storage/app/locations/
            Storage::disk('local')->putFileAs('locations', $file, $filename);

            $data['foto_path'] = 'locations/' . $filename;
        }

        LokasiRental::create($data);

        return redirect()->route('admin.lokasi-rental.index')->with('success', 'Lokasi rental created successfully!');
    }

    /**
     * Display the specified lokasi rental
     */
    public function show(LokasiRental $lokasiRental)
    {
        $lokasiRental->load('unitMotor.motor', 'reviews.user');
        return view('admin.lokasi-rental.show', compact('lokasiRental'));
    }

    /**
     * Show the form for editing the specified lokasi rental
     */
    public function edit(LokasiRental $lokasiRental)
    {
        return view('admin.lokasi-rental.edit', compact('lokasiRental'));
    }

    /**
     * Update the specified lokasi rental
     */
    public function update(Request $request, LokasiRental $lokasiRental)
    {
        $request->validate([
            'nama_cabang' => 'required|string|max:255',
            'alamat' => 'required|string',
            'deskripsi' => 'nullable|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'foto_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'nama_cabang' => $request->nama_cabang,
            'alamat' => $request->alamat,
            'deskripsi' => $request->deskripsi,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];

        // Handle foto upload
        if ($request->hasFile('foto_path')) {
            // Delete old photo if exists
            if ($lokasiRental->foto_path && Storage::disk('local')->exists($lokasiRental->foto_path)) {
                Storage::disk('local')->delete($lokasiRental->foto_path);
            }
        
            $file = $request->file('foto_path');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Store in storage/app/locations/
            Storage::disk('local')->putFileAs('locations', $file, $filename);

            $data['foto_path'] = 'locations/' . $filename;
        }

        $lokasiRental->update($data);

        return redirect()->route('admin.lokasi-rental.index')->with('success', 'Lokasi rental updated successfully!');
    }

    /**
     * Remove the specified lokasi rental
     */
    public function destroy(LokasiRental $lokasiRental)
    {
        // Delete photo if exists
        if ($lokasiRental->foto_path && Storage::disk('local')->exists($lokasiRental->foto_path)) {
            Storage::disk('local')->delete($lokasiRental->foto_path);
        }

        $lokasiRental->delete();

        return redirect()->route('admin.lokasi-rental.index')->with('success', 'Lokasi rental deleted successfully!');
    }
}