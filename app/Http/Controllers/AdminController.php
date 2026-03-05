<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\Dosen;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya admin yang bisa mengakses halaman ini');
        }

        // Ambil ringkasan data
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalMembers = User::where('role', 'asisten')->count();
        $totalMahasiswa = Mahasiswa::count();
        $totaldosen = Dosen::count();
        $totalMatakuliah = Matakuliah::count();

        return view('admin.dashboard', compact('totalUsers', 'totalAdmins', 'totalMembers', 'totalMahasiswa', 'totalMatakuliah', 'totaldosen'));
    }

    public function users()  
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        $users = User::where('role', 'asisten')->get();
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        return view('admin.users.create'); 
    }

    public function addUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'asisten',
        ]);

        return redirect()->route('admin.users')->with('success', 'Asisten berhasil ditambahkan!');
    }

    public function editUser($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users')->with('success', 'User berhasil diperbarui!');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Tidak bisa menghapus admin!');
        }

        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus!');
    }

    // Method lainnya untuk mahasiswa, dll
    public function mahasiswa()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        return view('admin.mahasiswa.index');
    }
}