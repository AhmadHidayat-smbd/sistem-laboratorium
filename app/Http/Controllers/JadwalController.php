<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Matakuliah;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwal = Jadwal::with('matakuliah')
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        return view('admin.jadwal.index', compact('jadwal'));
    }

    public function create()
    {
        $matakuliah = Matakuliah::active()->orderBy('nama')->get();
        return view('admin.jadwal.create', compact('matakuliah'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'matakuliah_id' => 'required|exists:matakuliah,id',
            'hari' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        // Simpan secara aman
        Jadwal::create([
            'matakuliah_id' => $request->matakuliah_id,
            'hari'          => $request->hari, // sudah format English
            'jam_mulai'     => $request->jam_mulai,
            'jam_selesai'   => $request->jam_selesai,
        ]);

        return redirect()->route('admin.jadwal')
            ->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $matakuliah = Matakuliah::active()->orderBy('nama')->get();

        return view('admin.jadwal.edit', compact('jadwal', 'matakuliah'));
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $request->validate([
            'matakuliah_id' => 'required|exists:matakuliah,id',
            'hari' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        $jadwal->update([
            'matakuliah_id' => $request->matakuliah_id,
            'hari'          => $request->hari,
            'jam_mulai'     => $request->jam_mulai,
            'jam_selesai'   => $request->jam_selesai,
        ]);

        return redirect()->route('admin.jadwal')
            ->with('success', 'Jadwal berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Jadwal::findOrFail($id)->delete();
        return back()->with('success', 'Jadwal berhasil dihapus!');
    }
}
