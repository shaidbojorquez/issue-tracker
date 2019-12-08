<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IssueComment extends Model
{
    protected $fillable = ['text', 'issue_id', 'user_id'];
}
