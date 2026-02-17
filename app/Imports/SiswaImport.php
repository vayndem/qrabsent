<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SiswaImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 6; // Loncat langsung ke baris data siswa pertama
    }

    public function model(array $row)
    {
        $nama = trim($row[1] ?? '');
        $nisn = trim($row[2] ?? '');

        // VALIDASI: Baris dibuang jika NISN bukan angka atau Nama kosong
        if (empty($nisn) || !is_numeric($nisn) || empty($nama)) {
            return null;
        }

        return new Siswa([
            'nama' => $nama,
            'nisn' => $nisn,
        ]);
    }
}
