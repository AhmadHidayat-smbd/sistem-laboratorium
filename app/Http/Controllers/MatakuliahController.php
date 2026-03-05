<?php

namespace App\Http\Controllers;

use App\Models\Matakuliah;
use App\Models\Dosen;
use Illuminate\Http\Request;

class MatakuliahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // All unique tahun_ajaran for dropdown
        $tahunAjaranList = Matakuliah::select('tahun_ajaran')
            ->distinct()
            ->orderBy('tahun_ajaran', 'desc')
            ->pluck('tahun_ajaran');

        // Determine filter value
        if ($request->has('tahun_ajaran')) {
            // User explicitly selected something
            $tahunAjaran = $request->query('tahun_ajaran');
            if ($tahunAjaran === 'all') {
                $tahunAjaran = ''; // show all
            }
        } else {
            // Default: tampilkan semua
            $tahunAjaran = '';
        }

        $matakuliah = Matakuliah::with('dosen')
            ->when($tahunAjaran, fn($q) => $q->where('tahun_ajaran', $tahunAjaran))
            ->orderBy('nama')
            ->get();

        return view('admin.matakuliah.index', compact('matakuliah', 'tahunAjaranList', 'tahunAjaran'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dosen = Dosen::orderBy('nama')->get();
        return view('admin.matakuliah.create', compact('dosen'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:255|unique:matakuliah,kode',
            'nama' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string|max:10|regex:/^\d{4}-[12]$/',
            'dosen_id' => 'nullable|exists:dosen,id',
        ], [
            'kode.required' => 'Kode mata kuliah wajib diisi',
            'kode.unique' => 'Kode mata kuliah sudah digunakan',
            'nama.required' => 'Nama mata kuliah wajib diisi',
            'tahun_ajaran.required' => 'Tahun ajaran wajib diisi',
            'tahun_ajaran.regex' => 'Format tahun ajaran harus YYYY-S (contoh: 2025-1)',
        ]);

        Matakuliah::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'tahun_ajaran' => $request->tahun_ajaran,
            'dosen_id' => $request->dosen_id,
        ]);

        return redirect()->route('admin.matakuliah')->with('success', 'Mata kuliah berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $matakuliah = Matakuliah::findOrFail($id);
        $dosen = Dosen::orderBy('nama')->get();
        return view('admin.matakuliah.edit', compact('matakuliah', 'dosen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $matakuliah = Matakuliah::findOrFail($id);

        $request->validate([
            'kode' => 'required|string|max:255|unique:matakuliah,kode,' . $id,
            'nama' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string|max:10|regex:/^\d{4}-[12]$/',
            'dosen_id' => 'nullable|exists:dosen,id',
        ], [
            'kode.required' => 'Kode mata kuliah wajib diisi',
            'kode.unique' => 'Kode mata kuliah sudah digunakan',
            'nama.required' => 'Nama mata kuliah wajib diisi',
            'tahun_ajaran.required' => 'Tahun ajaran wajib diisi',
            'tahun_ajaran.regex' => 'Format tahun ajaran harus YYYY-S (contoh: 2025-1)',
        ]);

        $matakuliah->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'tahun_ajaran' => $request->tahun_ajaran,
            'dosen_id' => $request->dosen_id,
        ]);

        return redirect()->route('admin.matakuliah')->with('success', 'Mata kuliah berhasil diperbarui!');
    }
    public function destroy($id)
    {
        $matakuliah = Matakuliah::findOrFail($id);
        $matakuliah->delete();

        return redirect()->route('admin.matakuliah')->with('success', 'Mata kuliah berhasil dihapus!');
    }

    /**
     * Toggle active/hidden status
     */
    public function toggleActive($id)
    {
        $matakuliah = Matakuliah::findOrFail($id);
        $matakuliah->is_active = !$matakuliah->is_active;
        $matakuliah->save();

        $status = $matakuliah->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Mata kuliah {$matakuliah->nama} berhasil {$status}!");
    }
}