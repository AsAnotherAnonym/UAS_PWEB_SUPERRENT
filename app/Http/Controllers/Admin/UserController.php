<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LokasiRental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        $users = User::with('lokasiRental')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $lokasiRentals = LokasiRental::all();
        return view('admin.users.create', compact('lokasiRentals'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users|alpha_dash',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'role' => 'required|in:admin,karyawan,user',
            'lokasi_rental_id' => 'nullable|exists:lokasi_rental,id',
            'foto_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'alamat' => $request->alamat,
            'role' => $request->role,
            'lokasi_rental_id' => $request->role === 'karyawan' ? $request->lokasi_rental_id : null,
        ];

        // Handle foto upload
        if ($request->hasFile('foto_path')) {
            $file = $request->file('foto_path');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Store in storage/app/users/
            Storage::disk('local')->putFileAs('users', $file, $filename);

            $data['foto_path'] = 'users/' . $filename;
        }

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $user->load('lokasiRental', 'penyewaan', 'reviews');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        $lokasiRentals = LokasiRental::all();
        return view('admin.users.edit', compact('user', 'lokasiRentals'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id . '|alpha_dash',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'role' => 'required|in:admin,karyawan,user',
            'lokasi_rental_id' => 'nullable|exists:lokasi_rental,id',
            'foto_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'alamat' => $request->alamat,
            'role' => $request->role,
            'lokasi_rental_id' => $request->role === 'karyawan' ? $request->lokasi_rental_id : null,
        ];

        // Update password if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle foto upload
        if ($request->hasFile('foto_path')) {
            // Delete old photo if exists
            if ($user->foto_path && Storage::disk('local')->exists($user->foto_path)) {
                Storage::disk('local')->delete($user->foto_path);
            }
        
            $file = $request->file('foto_path');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Store in storage/app/users/
            Storage::disk('local')->putFileAs('users', $file, $filename);

            $data['foto_path'] = 'users/' . $filename;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Delete photo if exists
        if ($user->foto_path && Storage::disk('local')->exists($user->foto_path)) {
            Storage::disk('local')->delete($user->foto_path);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    }
}