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
        $siswa = Siswa::where('nama', $request->nama)->first();

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Nama tidak terdaftar di sistem kami.'
            ], 404);
        }

        $sudahAbsen = Absen::where('id_nama', $siswa->id)
            ->where('tanggal', Carbon::now()->format('Y-m-d'))
            ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa atas nama ' . $siswa->nama . ' sudah absen hari ini.'
            ], 400);
        }

        Absen::create([
            'id_nama' => $siswa->id,
            'nama'    => $siswa->nama,
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'waktu'   => Carbon::now()->format('H:i:s'),
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
