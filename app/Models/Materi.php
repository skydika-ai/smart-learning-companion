<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materis';

    protected $fillable = [
        'user_id',
        'judul',
        'file_path',
        'teks_ekstrak',
        'ringkasan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kuis()
    {
        return $this->hasMany(Kuis::class);
    }
}