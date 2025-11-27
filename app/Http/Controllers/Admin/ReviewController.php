<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews
     */
    public function index(Request $request)
    {
        $query = Review::with('user', 'lokasiRental');

        // Filter by kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(10);
        $kategoris = Review::getKategoriOptions();
        
        return view('admin.reviews.index', compact('reviews', 'kategoris'));
    }

    /**
     * Display the specified review
     */
    public function show(Review $review)
    {
        $review->load('user', 'lokasiRental');
        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Remove the specified review
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted successfully!');
    }
}