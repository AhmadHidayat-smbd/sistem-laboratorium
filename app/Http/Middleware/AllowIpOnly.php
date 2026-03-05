<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AllowIpOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Hanya cek jika user adalah ASISTEN
        if (Auth::check() && Auth::user()->role === 'asisten') {
            $userIp = $request->ip();
            
            // Ambil daftar IP yang diizinkan dari .env
            $allowedIps = array_map('trim', explode(',', env('ALLOWED_IPS', '127.0.0.1')));
            
            // Tambahkan localhost agar tidak terkunci saat development
            if (!in_array('127.0.0.1', $allowedIps)) $allowedIps[] = '127.0.0.1';
            if (!in_array('::1', $allowedIps)) $allowedIps[] = '::1';

            // Jika IP TIDAK terdaftar
            if (!in_array($userIp, $allowedIps)) {
                
                $message = "Akses Ditolak! Anda harus terhubung ke jaringan Internet Kampus untuk mengelola absensi.";
                
                // Jika request dari Browser/Halaman Biasa
                if (!$request->expectsJson()) {
                    return redirect()->back()->with('error', $message);
                }
                
                // Jika request dari Ajax (halaman scan RFID)
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 403);
            }
        }

        return $next($request);
    }
}