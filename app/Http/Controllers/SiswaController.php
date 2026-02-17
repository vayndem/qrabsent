<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    // Menampilkan daftar siswa
    public function index()
    {
        $siswas = Siswa::all();
        return view('siswa.index', compact('siswas'));
    }

    // Menyimpan data siswa baru
    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:255',
            'nisn'  => 'required|unique:siswas,nisn',
        ]);

        Siswa::create($request->all());

        return redirect()->back()->with('success', 'Data siswa berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nisn' => 'required|unique:siswas,nisn,' . $id,
        ]);

        $siswa = Siswa::findOrFail($id);
        $siswa->update($request->all());

        return redirect()->back()->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();

        return redirect()->back()->with('success', 'Siswa berhasil dihapus dari database!');
    }
}
