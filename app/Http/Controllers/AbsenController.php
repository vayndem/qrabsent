<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Exports\AbsensiExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class AbsenController extends Controller
{
    public function store(Request $request)
    {
        $input = preg_replace('/\s+/', ' ', trim($request->nama));

        if (!$input) {
            return response()->json(['success' => false, 'message' => 'Input tidak boleh kosong.'], 400);
        }

        $siswa = null;

        if (is_numeric($input)) {
            $siswa = Siswa::where('nisn', $input)->first();
        } else {
            $parts = explode(' ', $input);
            $lastPart = end($parts);

            if (is_numeric($lastPart)) {
                $siswa = Siswa::where('nisn', $lastPart)->first();
            }

            if (!$siswa) {
                $siswa = Siswa::where('nama', $input)
                    ->orWhere('nama', 'LIKE', '%' . $input . '%')
                    ->first();
            }
        }

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa tidak ditemukan. Pastikan Nama atau NISN benar.'
            ], 404);
        }

        $sudahAbsen = Absen::where('id_nama', $siswa->id)
            ->where('tanggal', \Carbon\Carbon::now()->format('Y-m-d'))
            ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa ' . $siswa->nama . ' sudah absen hari ini.'
            ], 400);
        }

        Absen::create([
            'id_nama' => $siswa->id,
            'nama'    => $siswa->nama,
            'tanggal' => \Carbon\Carbon::now()->format('Y-m-d'),
            'waktu'   => \Carbon\Carbon::now()->format('H:i:s'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil! Absensi ' . $siswa->nama . ' telah dicatat.'
        ]);
    }

    public function exportExcel(Request $request)
    {
        $request->validate(['bulan' => 'required']);
        $bulan = $request->bulan;
        $fileName = "REKAP_ABSEN_" . str_replace('-', '_', $bulan) . ".xlsx";

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\AbsensiExport($bulan),
            $fileName
        );
    }
}
