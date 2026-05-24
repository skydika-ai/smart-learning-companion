<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['materi_id', 'user_id', 'judul_kuis'])]
class Kuis extends Model
{
    use HasFactory;

    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function soalKuis()
    {
        return $this->hasMany(SoalKuis::class);
    }

    public function hasilKuis()
    {
        return $this->hasMany(HasilKuis::class);
    }
}
