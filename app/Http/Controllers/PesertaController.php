<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use Illuminate\Http\Request;

class PesertaController extends Controller
{
    // ==========================
    // INDEX — LIST PESERTA
    // ==========================
    public function index(Request $request)
    {
        // All unique tahun_ajaran for dropdown
        $tahunAjaranList = Matakuliah::select('tahun_ajaran')
            ->distinct()
            ->orderBy('tahun_ajaran', 'desc')
            ->pluck('tahun_ajaran');

        // Determine filter value
        if ($request->has('tahun_ajaran')) {
            $tahunAjaran = $request->query('tahun_ajaran');
            if ($tahunAjaran === 'all') {
                $tahunAjaran = '';
            }
        } else {
            // Default: tampilkan semua
            $tahunAjaran = '';
        }

        $matakuliah = Matakuliah::with('mahasiswa')
            ->when($tahunAjaran, fn($q) => $q->where('tahun_ajaran', $tahunAjaran))
            ->orderBy('nama')
            ->get();

        return view('admin.peserta.index', compact('matakuliah', 'tahunAjaranList', 'tahunAjaran'));
    }

    // ==========================
    // CREATE — FORM TAMBAH PESERTA
    // ==========================
    public function create($matakuliah_id)
    {
        $matakuliah = Matakuliah::findOrFail($matakuliah_id);
        
        // Ambil ID mahasiswa yang sudah terdaftar
        $registeredIds = $matakuliah->mahasiswa()->pluck('mahasiswa_id')->toArray();
        
        // Ambil mahasiswa yang belum terdaftar
        $mahasiswa = Mahasiswa::whereNotIn('id', $registeredIds)
            ->orderBy('nama', 'asc')
            ->get();

        // view: resources/views/admin/tambah-peserta.blade.php
        return view('admin.peserta.create', compact('matakuliah', 'mahasiswa'));
    }

    // ==========================
    // STORE — SIMPAN PESERTA (BULK)
    // ==========================
    public function store(Request $request, $matakuliah_id)
    {
        $request->validate([
            'mahasiswa_ids' => 'required|array|min:1',
            'mahasiswa_ids.*' => 'exists:mahasiswa,id',
        ], [
            'mahasiswa_ids.required' => 'Pilih minimal 1 mahasiswa!',
            'mahasiswa_ids.min' => 'Pilih minimal 1 mahasiswa!',
        ]);

        $mk = Matakuliah::findOrFail($matakuliah_id);
        
        // Get mahasiswa yang sudah terdaftar
        $existingIds = $mk->mahasiswa()->pluck('mahasiswa_id')->toArray();
        
        // Filter hanya mahasiswa yang belum terdaftar
        $newIds = array_diff($request->mahasiswa_ids, $existingIds);
        
        if (empty($newIds)) {
            return back()->with('error', 'Semua mahasiswa yang dipilih sudah terdaftar pada mata kuliah ini!');
        }
        
        // Attach mahasiswa baru
        $mk->mahasiswa()->attach($newIds);
        
        $totalAdded = count($newIds);
        $totalSkipped = count($request->mahasiswa_ids) - $totalAdded;
        
        $message = "$totalAdded peserta berhasil ditambahkan!";
        if ($totalSkipped > 0) {
            $message .= " ($totalSkipped sudah terdaftar sebelumnya)";
        }

        return redirect()->route('admin.peserta', ['tahun_ajaran' => 'all'])->with('success', $message);
    }

    // ==========================
    // EDIT — FORM EDIT PESERTA
    // ==========================
    public function edit($matakuliah_id, $mahasiswa_id)
    {
        $matakuliah = Matakuliah::findOrFail($matakuliah_id);
        $mahasiswa = Mahasiswa::findOrFail($mahasiswa_id);
        
        // Ambil ID mahasiswa yang sudah terdaftar, kecuali mahasiswa yang sedang diedit
        $registeredIds = $matakuliah->mahasiswa()
            ->where('mahasiswa_id', '!=', $mahasiswa_id)
            ->pluck('mahasiswa_id')
            ->toArray();
        
        // Ambil mahasiswa yang belum terdaftar (atau yang sedang diedit ini sendiri)
        $allMahasiswa = Mahasiswa::whereNotIn('id', $registeredIds)
            ->orderBy('nama', 'asc')
            ->get();

        return view('admin.peserta.edit', compact('matakuliah', 'mahasiswa', 'allMahasiswa'));
    }

    // ==========================
    // UPDATE — UPDATE PESERTA
    // ==========================
    public function update(Request $request, $matakuliah_id, $mahasiswa_id)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswa,id',
        ]);

        $mk = Matakuliah::findOrFail($matakuliah_id);

        // Validasi tambahan: Jika mengganti mahasiswa, pastikan mahasiswa baru BELUM ada di matkul ini
        if ($request->mahasiswa_id != $mahasiswa_id) {
            $exists = $mk->mahasiswa()->where('mahasiswa_id', $request->mahasiswa_id)->exists();
            if ($exists) {
                return back()->with('error', 'Mahasiswa tersebut sudah terdaftar di mata kuliah ini!');
            }
        }

        // Jalankan update: hapus yang lama, pasang yang baru
        $mk->mahasiswa()->detach($mahasiswa_id);
        $mk->mahasiswa()->attach($request->mahasiswa_id);

        return redirect()->route('admin.peserta', ['tahun_ajaran' => 'all'])->with('success', 'Peserta berhasil diperbarui!');
    }

    // ==========================
    // DESTROY — HAPUS PESERTA
    // ==========================
    public function delete($matakuliah_id, $mahasiswa_id)
    {
        $mk = Matakuliah::findOrFail($matakuliah_id);

        $mk->mahasiswa()->detach($mahasiswa_id);

        return back()->with('success', 'Peserta berhasil dihapus!');
    }

    public function deleteAll($matakuliah_id)
    {
        $mk = Matakuliah::findOrFail($matakuliah_id);
        $mk->mahasiswa()->detach();

        return back()->with('success', 'Semua peserta dari mata kuliah ' . $mk->nama . ' berhasil dihapus!');
    }
}
