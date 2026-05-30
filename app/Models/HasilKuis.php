<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilKuis extends Model
{
    protected $fillable = [
        'kuis_id',
        'user_id',
        'skor',
        'jawaban_user',
        'waktu_pengerjaan',
    ];

    protected $casts = [
        'jawaban_user' => 'array',
    ];

    public function kuis()
    {
        return $this->belongsTo(Kuis::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}