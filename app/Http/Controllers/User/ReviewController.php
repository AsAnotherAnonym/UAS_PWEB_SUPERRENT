<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\LokasiRental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of user's reviews
     */
    public function index()
    {
        $user = Auth::user();

        $reviews = Review::where('user_id', $user->id)
            ->with('lokasiRental')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new review
     */
    public function create(Request $request)
    {
        $lokasiRentals = LokasiRental::all();
        $kategoris = Review::getKategoriOptions();
        
        // Pre-select lokasi from query params
        $selectedLokasiId = $request->get('lokasi_id');

        return view('user.reviews.create', compact('lokasiRentals', 'kategoris', 'selectedLokasiId'));
    }

    /**
     * Store a newly created review
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'lokasi_rental_id' => 'nullable|exists:lokasi_rental,id',
            'kategori' => 'required|in:tempat sewa,motor,website,lainnya',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:10',
        ]);

        Review::create([
            'user_id' => $user->id,
            'lokasi_rental_id' => $request->lokasi_rental_id,
            'kategori' => $request->kategori,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return redirect()->route('user.reviews.index')->with('success', 'Review submitted successfully!');
    }

    /**
     * Display the specified review
     */
    public function show(Review $review)
    {
        $user = Auth::user();

        // Check if review belongs to user
        if ($review->user_id !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        $review->load('lokasiRental');
        return view('user.reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified review
     */
    public function edit(Review $review)
    {
        $user = Auth::user();

        // Check if review belongs to user
        if ($review->user_id !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        $lokasiRentals = LokasiRental::all();
        $kategoris = Review::getKategoriOptions();

        return view('user.reviews.edit', compact('review', 'lokasiRentals', 'kategoris'));
    }

    /**
     * Update the specified review
     */
    public function update(Request $request, Review $review)
    {
        $user = Auth::user();

        // Check if review belongs to user
        if ($review->user_id !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'lokasi_rental_id' => 'nullable|exists:lokasi_rental,id',
            'kategori' => 'required|in:tempat sewa,motor,website,lainnya',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:10',
        ]);

        $review->update([
            'lokasi_rental_id' => $request->lokasi_rental_id,
            'kategori' => $request->kategori,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return redirect()->route('user.reviews.index')->with('success', 'Review updated successfully!');
    }

    /**
     * Remove the specified review
     */
    public function destroy(Review $review)
    {
        $user = Auth::user();

        // Check if review belongs to user
        if ($review->user_id !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        $review->delete();

        return redirect()->route('user.reviews.index')->with('success', 'Review deleted successfully!');
    }
}