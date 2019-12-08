<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    protected $fillable = ['title', 'description', 'type', 'priority', 'status', 'assignee_id'];

    // Relations

    public function docs()
    {
        return $this->morphMany(Doc::class, 'docable');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }
}
