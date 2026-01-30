<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kuis extends Model
{
    use HasFactory;

    protected $table = 'kuis';

    public $timestamps = false;

    protected $fillable = [
        'materi_id',
        'pertanyaan',
        'pilihan_a',
        'pilihan_b',
        'pilihan_c',
        'pilihan_d',
        'jawaban_benar',
        'poin',
    ];

    /**
     * Get the materi that owns this kuis
     */
    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }

    /**
     * Check if answer is correct
     */
    public function isCorrect(string $answer): bool
    {
        return strtoupper($answer) === strtoupper($this->jawaban_benar);
    }

    /**
     * Get all options as array
     */
    public function getOptionsAttribute(): array
    {
        return [
            'A' => $this->pilihan_a,
            'B' => $this->pilihan_b,
            'C' => $this->pilihan_c,
            'D' => $this->pilihan_d,
        ];
    }
}
