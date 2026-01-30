<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilKuis extends Model
{
    use HasFactory;

    protected $table = 'hasil_kuis';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'materi_id',
        'skor',
        'total_soal',
        'jawaban_detail',
    ];

    protected $casts = [
        'jawaban_detail' => 'array',
    ];

    /**
     * Get the user for this hasil kuis
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the materi for this hasil kuis
     */
    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }

    /**
     * Get percentage score
     */
    public function getPercentageAttribute(): float
    {
        if ($this->total_soal === 0)
            return 0;
        return round(($this->skor / ($this->total_soal * 10)) * 100, 2);
    }

    /**
     * Check if passed (score >= 70%)
     */
    public function isPassed(): bool
    {
        return $this->percentage >= 70;
    }
}
