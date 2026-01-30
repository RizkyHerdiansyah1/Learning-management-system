<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $table = 'enrollment';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'kelas_id',
    ];

    /**
     * Get the user (mahasiswa) for this enrollment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the kelas for this enrollment
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
