<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Imports\SiswaImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;

class ImportSiswa extends Command
{
    // Ini adalah 'nama' perintah yang akan dipanggil di terminal
    protected $signature = 'import:siswa {file}';
    protected $description = 'Import data siswa dari file Excel';

    public function handle()
    {
        $filePath = $this->argument('file');

        if (!File::exists($filePath)) {
            $this->error("File tidak ditemukan!");
            return;
        }

        Excel::import(new SiswaImport, $filePath);

        $this->info("Mantap! Data siswa berhasil diimport tanpa sampah.");
    }
}
