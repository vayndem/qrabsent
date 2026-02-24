<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $bulanDipilih = $request->get('bulan', date('Y-m'));

        $rekapAbsen = Absen::where('tanggal', 'like', $bulanDipilih . '%')
            ->orderBy('nama', 'asc')
            ->orderBy('tanggal', 'asc')
            ->orderBy('waktu', 'asc')
            ->get()
            ->groupBy('nama');

        return view('dashboard', compact('rekapAbsen', 'bulanDipilih'));
    }
}
