<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\LokasiRental;
use Illuminate\Http\Request;

class LokasiRentalController extends Controller
{
    /**
     * Display a listing of lokasi rental with map
     */
    public function index(Request $request)
    {
        $query = LokasiRental::withCount('unitMotor');

        // Search by nama_cabang
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_cabang', 'like', '%' . $request->search . '%');
        }

        $lokasiRentals = $query->get();

        return view('user.lokasi-rental.index', compact('lokasiRentals'));
    }

    /**
     * Display the specified lokasi rental detail
     */
    public function show(LokasiRental $lokasiRental)
    {
        $lokasiRental->load([
            'unitMotor' => function ($query) {
                $query->where('status', 'tersedia')->with('motor');
            },
            'reviews' => function ($query) {
                $query->with('user')->orderBy('created_at', 'desc');
            }
        ]);

        return view('user.lokasi-rental.show', compact('lokasiRental'));
    }
}