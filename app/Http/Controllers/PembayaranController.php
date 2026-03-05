<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PembayaranImport;
use Carbon\Carbon;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $angkatan = $request->input('angkatan');

        $query = Pembayaran::select(
            'nim',
            'nama',
            DB::raw('count(*) as total_kali_bayar'),
            DB::raw('sum(nominal) as total_nominal')
        );

        // Filter Search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        // Filter Angkatan (Ambil 2 digit pertama NIM)
        if ($angkatan) {
            $query->where('nim', 'like', "{$angkatan}%");
        }

        $pembayarans = $query->groupBy('nim', 'nama')
            ->orderBy('nim', 'asc')
            ->get(); // Hapus pagination

        // Ambil daftar angkatan yang ada (2 digit pertama NIM)
        $daftarAngkatan = Pembayaran::select(DB::raw('LEFT(nim, 2) as angkatan'))
            ->groupBy('angkatan')
            ->orderBy('angkatan', 'desc')
            ->pluck('angkatan');

        return view('admin.pembayaran.index', compact('pembayarans', 'search', 'angkatan', 'daftarAngkatan'));
    }

    public function show($nim)
    {
        // Get all payment details for a specific NIM
        $pembayarans = Pembayaran::where('nim', $nim)
            ->orderBy('tanggal_pembayaran', 'desc')
            ->get();

        if ($pembayarans->isEmpty()) {
            return redirect()->route('admin.pembayaran.index')->with('error', 'Data tidak ditemukan.');
        }

        $nama = $pembayarans->first()->nama;

        return view('admin.pembayaran.show', compact('pembayarans', 'nim', 'nama'));
    }

    public function create()
    {
        return view('admin.pembayaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string|max:50',
            'nominal' => 'required|numeric|min:0',
            'tanggal_pembayaran' => 'required|date',
        ]);

        // Validasi Duplikasi: Cek apakah mahasiswa ini sudah pernah bayar di tahun ajaran ini
        $exists = Pembayaran::where('nim', $request->nim)
                           ->where('tahun_ajaran', $request->tahun_ajaran)
                           ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Mahasiswa dengan NIM {$request->nim} sudah memiliki catatan pembayaran untuk Tahun Ajaran {$request->tahun_ajaran}.");
        }

        Pembayaran::create($request->all());

        return redirect()->route('admin.pembayaran.index')->with('success', 'Data pembayaran berhasil ditambahkan.');
    }

    public function edit(Pembayaran $pembayaran)
    {
        return view('admin.pembayaran.edit', compact('pembayaran'));
    }

    public function update(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string|max:50',
            'nominal' => 'required|numeric|min:0',
            'tanggal_pembayaran' => 'required|date',
        ]);

        $pembayaran->update($request->all());

        return redirect()->route('admin.pembayaran.show', $pembayaran->nim)
            ->with('success', 'Data pembayaran berhasil diperbarui.');
    }

    public function destroy(Pembayaran $pembayaran)
    {
        $nim = $pembayaran->nim;
        $pembayaran->delete();

        // Check if there are still records for this nim
        $count = Pembayaran::where('nim', $nim)->count();

        if ($count > 0) {
            return redirect()->route('admin.pembayaran.show', $nim)
                ->with('success', 'Data pembayaran berhasil dihapus.');
        } else {
            return redirect()->route('admin.pembayaran.index')
                ->with('success', 'Semua data pembayaran untuk mahasiswa tersebut telah dihapus.');
        }
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new PembayaranImport, $request->file('file'));
            return redirect()->route('admin.pembayaran.index')
                ->with('success', 'Data pembayaran berhasil diimport dari Excel.');
        } catch (\Exception $e) {
            return redirect()->route('admin.pembayaran.index')
                ->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }
}
