<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'password' => 'required',
        ]);

        $identifier = $request->identifier;
        $password = $request->password;

        // 1. Coba login sebagai User (Admin/Asisten) menggunakan Email
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            if (Auth::guard('web')->attempt(['email' => $identifier, 'password' => $password])) {
                $user = Auth::user();
                
                $request->session()->regenerate();
                if ($user->role === 'admin') {
                    return redirect()->route('admin.dashboard');
                }
                return redirect()->route('asisten.dashboard');
            }
        } 
        
        // 2. Coba login sebagai Mahasiswa menggunakan NIM 
        // Atau jika identifier adalah NIM yang valid
        if (is_numeric($identifier)) {
            if (Auth::guard('mahasiswa')->attempt(['nim' => $identifier, 'password' => $password])) {
                $request->session()->regenerate();
                return redirect()->route('mahasiswa.dashboard');
            }
        }
        
        // 3. Coba login sebagai Dosen (menggunakan Email)
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            if (Auth::guard('dosen')->attempt(['email' => $identifier, 'password' => $password])) {

                $request->session()->regenerate();
                return redirect()->route('dosen.dashboard');
            }
        }

        return back()->withErrors([
            'identifier' => 'Username, Email, atau Password salah',
        ])->onlyInput('identifier');
    }

    public function logout(Request $request)
    {
        if (Auth::guard('mahasiswa')->check()) {
            Auth::guard('mahasiswa')->logout();
        } else {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}