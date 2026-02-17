<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    protected $fillable = ['nama', 'kelas', 'nisn'];

    /**
     * Relasi ke model Absen
     */
    public function absens(): HasMany
    {
        // Kita mendefinisikan foreign key-nya adalah 'id_nama'
        return $this->hasMany(Absen::class, 'id_nama', 'id');
    }
}
