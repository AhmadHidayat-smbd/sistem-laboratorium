<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Matakuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules\Password;

class MahasiswaPortalController extends Controller
{
    public function dashboard()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        
        return view('mahasiswa.dashboard', compact('mahasiswa'));
    }

    public function profile()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        return view('mahasiswa.profile', compact('mahasiswa'));
    }

    public function updatePassword(Request $request)
    {
     $request->validate([
     'current_password' => ['required', 'current_password:mahasiswa'],
         'password' => ['required', 'confirmed', 'min:6'],
        ]);

        $mahasiswa = Auth::guard('mahasiswa')->user();
        $mahasiswa->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diperbarui!');
    }
}
