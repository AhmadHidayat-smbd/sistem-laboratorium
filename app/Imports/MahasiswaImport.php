<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MahasiswaImport implements ToCollection, WithHeadingRow
{
    public array $results = [
        'success' => 0,
        'skipped' => 0,
        'errors'  => [],
    ];

    public function collection(Collection $rows)
    {
        // ── 1. Validasi: cek NIM duplikat DALAM file Excel ───────────
        $allNims = $rows->pluck('nim')
            ->filter()                          // buang yang kosong
            ->map(fn($v) => trim((string) $v)); // trim & string

        $duplicateInExcel = $allNims->duplicates()->unique()->values();

        if ($duplicateInExcel->isNotEmpty()) {
            foreach ($duplicateInExcel as $dup) {
                $this->results['errors'][] = "NIM \"{$dup}\" muncul lebih dari sekali di file Excel.";
            }
            // Tetap lanjutkan proses, tapi baris dengan NIM duplikat akan di-skip
        }

        // ── 2. Ambil NIM yang sudah ada di database ──────────────────
        $existingNims = Mahasiswa::pluck('nim')->map(fn($v) => (string) $v)->toArray();
        $existingRfids = Mahasiswa::whereNotNull('rfid_uid')
            ->pluck('rfid_uid')
            ->map(fn($v) => (string) $v)
            ->toArray();

        // ── 3. Tracking NIM yang sudah diproses dalam batch ini ──────
        $processedNims = [];
        $processedRfids = [];

        $rowNumber = 1; // heading row sudah ke-skip, jadi ini baris data pertama

        foreach ($rows as $row) {
            $rowNumber++;

            $nama     = trim($row['nama'] ?? '');
            $nim      = trim((string) ($row['nim'] ?? ''));
            $rfid_uid = trim((string) ($row['rfid_uid'] ?? ''));

            // ── Validasi baris kosong ────────────────────────────────
            if (empty($nama) && empty($nim)) {
                continue; // skip baris kosong
            }

            // ── Validasi field wajib ────────────────────────────────
            $errors = [];

            if (empty($nama)) {
                $errors[] = "Baris {$rowNumber}: Nama wajib diisi.";
            }

            if (empty($nim)) {
                $errors[] = "Baris {$rowNumber}: NIM wajib diisi.";
            }

            if (empty($rfid_uid)) {
                $errors[] = "Baris {$rowNumber}: RFID UID wajib diisi.";
            }

            if (!empty($errors)) {
                $this->results['errors'] = array_merge($this->results['errors'], $errors);
                $this->results['skipped']++;
                continue;
            }

            // ── Cek duplikat NIM di database ────────────────────────
            if (in_array($nim, $existingNims)) {
                $this->results['errors'][] = "Baris {$rowNumber}: NIM \"{$nim}\" ({$nama}) sudah terdaftar di database.";
                $this->results['skipped']++;
                continue;
            }

            // ── Cek duplikat NIM di batch ini (duplikat dalam Excel) ─
            if (in_array($nim, $processedNims)) {
                $this->results['errors'][] = "Baris {$rowNumber}: NIM \"{$nim}\" ({$nama}) duplikat dalam file Excel, baris ini di-skip.";
                $this->results['skipped']++;
                continue;
            }

            // ── Cek duplikat RFID di database ───────────────────────
            if (in_array($rfid_uid, $existingRfids)) {
                $this->results['errors'][] = "Baris {$rowNumber}: RFID \"{$rfid_uid}\" ({$nama}) sudah terdaftar di database.";
                $this->results['skipped']++;
                continue;
            }

            // ── Cek duplikat RFID di batch ini ──────────────────────
            if (in_array($rfid_uid, $processedRfids)) {
                $this->results['errors'][] = "Baris {$rowNumber}: RFID \"{$rfid_uid}\" ({$nama}) duplikat dalam file Excel, baris ini di-skip.";
                $this->results['skipped']++;
                continue;
            }

            // ── Simpan mahasiswa ────────────────────────────────────
            Mahasiswa::create([
                'nama'     => $nama,
                'nim'      => $nim,
                'rfid_uid' => $rfid_uid,
                'password' => Hash::make($nim), // password default = NIM
            ]);

            $processedNims[]  = $nim;
            $processedRfids[] = $rfid_uid;
            $this->results['success']++;
        }
    }
}
