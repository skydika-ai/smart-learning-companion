<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kuis extends Model
{
    use HasFactory;

    /**
     * Mass assignable
     */
    protected $fillable = [
        'materi_id',
        'user_id',
        'judul_kuis',
        'html_quiz',
    ];

    /**
     * Relasi ke materi
     */
    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }

    /**
     * Relasi ke user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke soal kuis
     */
    public function soalKuis()
    {
        return $this->hasMany(SoalKuis::class);
    }

    /**
     * Relasi ke hasil kuis
     */
    public function hasilKuis()
    {
        return $this->hasMany(HasilKuis::class);
    }
}