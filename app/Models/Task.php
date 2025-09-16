<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'description', 'isCompleted'];

    public function user()
    {
        return $this->belongsTo(Task::class);
    }
}
