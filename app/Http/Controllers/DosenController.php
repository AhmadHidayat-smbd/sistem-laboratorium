<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DosenController extends Controller
{
    /**
     * Display a listing of the dosen.
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        
        $dosen = Dosen::query()
            ->when($search, fn($q) => $q->where('nama', 'like', "%{$search}%")
                                        ->orWhere('email', 'like', "%{$search}%"))
            ->orderBy('nama')
            ->get();

        return view('admin.dosen.index', compact('dosen', 'search'));
    }

    /**
     * Show the form for creating a new dosen.
     */
    public function create()
    {
        return view('admin.dosen.create');
    }

    /**
     * Store a newly created dosen in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:dosen,email',
            'password' => 'required|min:6',
            'rfid_uid' => 'nullable|string|max:50|unique:dosen,rfid_uid',
        ]);

        Dosen::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rfid_uid' => $request->rfid_uid,
        ]);

        return redirect()->route('admin.dosen')->with('success', 'Data dosen berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified dosen.
     */
    public function edit($id)
    {
        $dosen = Dosen::findOrFail($id);
        return view('admin.dosen.edit', compact('dosen'));
    }

    /**
     * Update the specified dosen in storage.
     */
    public function update(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:dosen,email,' . $id,
            'password' => 'nullable|min:6',
            'rfid_uid' => 'nullable|string|max:50|unique:dosen,rfid_uid,' . $id,
        ]);

        $data = [
            'nama' => $request->nama,
            'email' => $request->email,
            'rfid_uid' => $request->rfid_uid,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $dosen->update($data);

        return redirect()->route('admin.dosen')->with('success', 'Data dosen berhasil diperbarui!');
    }

    /**
     * Remove the specified dosen from storage.
     */
    public function destroy($id)
    {
        $dosen = Dosen::findOrFail($id);
        $dosen->delete();

        return redirect()->route('admin.dosen')->with('success', 'Data dosen berhasil dihapus!');
    }
}
