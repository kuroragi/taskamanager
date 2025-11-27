<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskLog extends Model
{
    protected $fillable = ['task_id', 'field', 'before', 'after'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
