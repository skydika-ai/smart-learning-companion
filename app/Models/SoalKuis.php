<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SoalKuis extends Model
{
    use HasFactory;

    protected $table = 'soal_kuis';

    protected $fillable = [

        'kuis_id',

        'pertanyaan',

        'opsi_a',
        'opsi_b',
        'opsi_c',
        'opsi_d',

        'jawaban_benar',

        'penjelasan',

    ];

    /**
     * Relasi ke kuis
     */
    public function kuis()
    {
        return $this->belongsTo(Kuis::class);
    }
}