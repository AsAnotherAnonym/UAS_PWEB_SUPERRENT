<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\LokasiRental;
use App\Models\UnitMotor;

class FileController extends Controller
{
    /**
     * Serve protected user profile photos
     */
    public function serveUserPhoto($filename)
    {
        // Find user by photo path
        $user = User::where('foto_path', 'users/' . $filename)->first();
        
        if (!$user) {
            abort(404);
        }
        
        // Authorization: Can view own photo, or admin/karyawan can view any
        if (Auth::id() !== $user->id && !Auth::user()->isAdmin() && !Auth::user()->isKaryawan()) {
            abort(403, 'Unauthorized access');
        }
        
        $path = storage_path('app/users/' . $filename);
        
        if (!file_exists($path)) {
            abort(404, 'File not found at path: ' . $path);
        }
        
        return response()->file($path);
    }
    
    /**
     * Serve location photos (public - anyone can view)
     */
    public function serveLocationPhoto($filename)
    {
        $path = storage_path('app/locations/' . $filename);
        
        if (!file_exists($path)) {
            abort(404);
        }
        
        return response()->file($path);
    }
    
    /**
     * Serve unit photos (public - anyone can view)
     */
    public function serveUnitPhoto($filename)
    {
        $path = storage_path('app/units/' . $filename);
        
        if (!file_exists($path)) {
            abort(404);
        }
        
        return response()->file($path);
    }
}