<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Imports\MahasiswaImport;
use Maatwebsite\Excel\Facades\Excel;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        $search = $request->query('search');
        $perPage = $request->query('per_page', 10);

        $mahasiswa = Mahasiswa::when($search, function($query) use ($search) {
                return $query->where('nama', 'like', '%' . $search . '%')
                             ->orWhere('nim', 'like', '%' . $search . '%');
            })
            ->orderBy('nama', 'asc')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.mahasiswa.index', compact('mahasiswa', 'search', 'perPage'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        return view('admin.mahasiswa.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        $request->validate([
            'nama' => 'required|string|max:255|unique:mahasiswa,nama',
            'nim' => 'required|string|unique:mahasiswa,nim',
            'rfid_uid' => 'required|string|unique:mahasiswa,rfid_uid',
            'password' => 'nullable|string|min:6',
        ], [
            'nama.required' => 'Nama mahasiswa wajib diisi',
            'nama.unique' => 'Nama mahasiswa sudah terdaftar',
            'nim.required' => 'NIM wajib diisi',
            'nim.unique' => 'NIM sudah terdaftar',
            'rfid_uid.required' => 'RFID UID wajib diisi',
            'rfid_uid.unique' => 'RFID UID sudah terdaftar',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        Mahasiswa::create([
            'nama' => $request->nama,
            'nim' => $request->nim,
            'rfid_uid' => $request->rfid_uid,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password ?: $request->nim),
        ]);

        return redirect()->route('admin.mahasiswa')->with('success', 'Mahasiswa berhasil ditambahkan!');
    }

    public function edit($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        $mahasiswa = Mahasiswa::findOrFail($id);
        return view('admin.mahasiswa.edit', compact('mahasiswa')); 
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        $mahasiswa = Mahasiswa::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255|unique:mahasiswa,nama,' . $mahasiswa->id,
            'nim' => 'required|string|unique:mahasiswa,nim,' . $mahasiswa->id,
            'rfid_uid' => 'required|string|unique:mahasiswa,rfid_uid,' . $mahasiswa->id,
            'password' => 'nullable|string|min:6',
        ], [
            'nama.required' => 'Nama mahasiswa wajib diisi',
            'nama.unique' => 'Nama mahasiswa sudah terdaftar',
            'nim.required' => 'NIM wajib diisi',
            'nim.unique' => 'NIM sudah terdaftar',
            'rfid_uid.required' => 'RFID UID wajib diisi',
            'rfid_uid.unique' => 'RFID UID sudah terdaftar',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        $data = [
            'nama' => $request->nama,
            'nim' => $request->nim,
            'rfid_uid' => $request->rfid_uid,
        ];

        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $mahasiswa->update($data);

        return redirect()->route('admin.mahasiswa')->with('success', 'Data mahasiswa berhasil diperbarui!');
    }

    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->delete();

        return redirect()->route('admin.mahasiswa')->with('success', 'Mahasiswa berhasil dihapus!');
    }

    /**
     * Import Mahasiswa dari file Excel
     */
    public function importExcel(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file.required' => 'File Excel wajib dipilih!',
            'file.mimes'    => 'Format file harus .xlsx, .xls, atau .csv!',
            'file.max'      => 'Ukuran file maksimal 5MB!',
        ]);

        $import = new MahasiswaImport();
        Excel::import($import, $request->file('file'));

        $results = $import->results;

        // Build pesan hasil
        $messages = [];
        if ($results['success'] > 0) {
            $messages[] = "{$results['success']} mahasiswa berhasil diimport.";
        }
        if ($results['skipped'] > 0) {
            $messages[] = "{$results['skipped']} baris di-skip.";
        }

        $mainMessage = implode(' ', $messages);

        if ($results['success'] > 0 && empty($results['errors'])) {
            return redirect()->route('admin.mahasiswa')->with('success', $mainMessage);
        }

        if ($results['success'] > 0 && !empty($results['errors'])) {
            return redirect()->route('admin.mahasiswa')
                ->with('success', $mainMessage)
                ->with('import_errors', $results['errors']);
        }

        // Semua gagal
        return redirect()->route('admin.mahasiswa')
            ->with('error', 'Import gagal. ' . $mainMessage)
            ->with('import_errors', $results['errors']);
    }
}