<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    use HasFactory;

    protected $table = 'progress';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'materi_id',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    /**
     * Get the user for this progress
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the materi for this progress
     */
    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }

    /**
     * Check if completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if in progress
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Check if locked
     */
    public function isLocked(): bool
    {
        return $this->status === 'locked';
    }

    /**
     * Mark as completed
     */
    public function markCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'tanggal_selesai' => now(),
        ]);
    }

    /**
     * Mark as in progress
     */
    public function markInProgress(): void
    {
        $this->update([
            'status' => 'in_progress',
            'tanggal_mulai' => now(),
        ]);
    }
}
