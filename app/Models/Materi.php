<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materi';

    public $timestamps = false;

    protected $fillable = [
        'kelas_id',
        'judul',
        'tipe',
        'konten',
        'urutan',
        'durasi_menit',
    ];

    /**
     * Get the kelas that owns this materi
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    /**
     * Get all kuis for this materi (if tipe = quiz)
     */
    public function kuis()
    {
        return $this->hasMany(Kuis::class);
    }

    /**
     * Get progress records for this materi
     */
    public function progress()
    {
        return $this->hasMany(Progress::class);
    }

    /**
     * Get hasil kuis for this materi
     */
    public function hasilKuis()
    {
        return $this->hasMany(HasilKuis::class);
    }

    /**
     * Check if this materi is video
     */
    public function isVideo(): bool
    {
        return $this->tipe === 'video';
    }

    /**
     * Check if this materi is text
     */
    public function isText(): bool
    {
        return $this->tipe === 'text';
    }

    /**
     * Check if this materi is quiz
     */
    public function isQuiz(): bool
    {
        return $this->tipe === 'quiz';
    }

    /**
     * Get next materi in sequence
     */
    public function nextMateri()
    {
        return self::where('kelas_id', $this->kelas_id)
            ->where('urutan', $this->urutan + 1)
            ->first();
    }

    /**
     * Get previous materi in sequence
     */
    public function previousMateri()
    {
        return self::where('kelas_id', $this->kelas_id)
            ->where('urutan', $this->urutan - 1)
            ->first();
    }
}
