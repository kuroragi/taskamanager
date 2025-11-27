<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'difficulty',
        'desire',
        'obligation',
        'energy',
        'deadline',
        'status',
        'priority_score',
        'completed_at',
    ];

    protected $casts = [
        'difficulty' => 'integer',
        'desire' => 'integer',
        'obligation' => 'integer',
        'priority_score' => 'float',
        'deadline' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public const STATUS_TODO = 'todo';
    public const STATUS_DOING = 'doing';
    public const STATUS_DONE = 'done';
    public const STATUS_HOLD = 'hold';
    public const STATUS_DROPPED = 'dropped';

    /**
     * Scope: urutkan berdasarkan priority_score desc.
     */
    public function scopeOrderByPriority($query)
    {
        return $query->orderByDesc('priority_score');
    }

    /**
     * Hitung urgency berbasis kedekatan deadline (linear awal).
     */
    public function calculateUrgency(): int
    {
        if (!$this->deadline) {
            return 0;
        }

        $now = Carbon::now();
        $deadline = Carbon::parse($this->deadline);

        // days_left: jika lewat deadline, anggap 0
        $daysLeft = max(0, $now->diffInDays($deadline, false) < 0 ? 0 : $now->diffInDays($deadline));

        // urgency = clamp(10 - daysLeft, 0, 10)
        $urgency = max(0, min(10, 10 - $daysLeft));
        return (int) $urgency;
    }

    /**
     * Hitung skor prioritas berdasarkan formula yang ditetapkan.
     */
    public function calculatePriority(): float
    {
        $urgency = $this->calculateUrgency();

        $obligation = (int) $this->obligation;
        $desire = (int) $this->desire;
        $difficulty = (int) $this->difficulty;

        $score = ($urgency * 3) + ($obligation * 2) + $desire - $difficulty;
        return (float) $score;
    }

    /**
     * Update field priority_score dengan hasil kalkulasi terbaru.
     */
    public function refreshPriorityScore(): void
    {
        $this->priority_score = $this->calculatePriority();
    }
}
