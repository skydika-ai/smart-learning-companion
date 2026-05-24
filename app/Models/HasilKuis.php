<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['kuis_id', 'user_id', 'skor', 'jawaban_user'])]
class HasilKuis extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'jawaban_user' => 'array',
        ];
    }

    public function kuis()
    {
        return $this->belongsTo(Kuis::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
