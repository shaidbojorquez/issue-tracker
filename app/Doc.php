<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doc extends Model
{
    protected $fillable = [
        'extension',
        'name',
        'size',
        'title',
        'observations'
    ];

    protected $appends = ['file_name', 'type', 'url_file_link'];

    // Mutators & Getters

    public function getFileNameAttribute()
    {
        switch ($this->docable_type) {
            case Issue::class:
                $issue = Issue::findOrFail($this->docable_id);
                return 'issue_' . $issue->id . DIRECTORY_SEPARATOR . $this->name . '.' . $this->extension;
                break;

            default:
                # code...
                break;
        }
    }

    public function getUrlFileLinkAttribute()
    {
        $name = $this->file_name;
        return src($name, $this->type);
    }

    public function getTypeAttribute()
    {
        switch ($this->docable_type) {
            case Issue::class:
                return 'issues';
                break;

            default:
                # code...
                break;
        }
    }

    // Relations

    public function docable()
    {
        return $this->morphTo();
    }
}
