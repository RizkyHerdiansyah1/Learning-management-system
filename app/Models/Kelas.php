<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    public $timestamps = false;

    protected $fillable = [
        'dosen_id',
        'nama_kelas',
        'deskripsi',
        'kategori',
        'total_courses',
    ];

    /**
     * Get the dosen who owns this kelas
     */
    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    /**
     * Get all materi in this kelas
     */
    public function materi()
    {
        return $this->hasMany(Materi::class)->orderBy('urutan');
    }

    /**
     * Get all enrollments for this kelas
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get enrolled students
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollment')
            ->withTimestamps();
    }

    /**
     * Get total materi count
     */
    public function getTotalMateriAttribute(): int
    {
        return $this->materi()->count();
    }

    /**
     * Get total student count
     */
    public function getTotalStudentsAttribute(): int
    {
        return $this->enrollments()->count();
    }
}
