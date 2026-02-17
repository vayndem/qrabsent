<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absen extends Model
{
    protected $fillable = ['nama', 'id_nama', 'tanggal', 'waktu'];

    /**
     * Relasi balik ke model Siswa
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_nama', 'id');
    }
}
