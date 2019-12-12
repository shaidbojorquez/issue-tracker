<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'begin_date', 'end_date', 'description', 'status'];
    #Eveto para delete, se dispara cuando mande la acción de eliminar un registro. Cu
    public static function boot() {
        parent::boot();

        static::deleting(function($project) { // before delete() method call this
            $project->users()->detach();
            $issues = $project->issues;
            foreach ($issues as $issue) {
                $issue->users()->detach();
                $issue->delete();
            }
        });
    }

    /**
     * Users relationship.
     *
     * A project may have one or more users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function users() {
        return $this->belongsToMany(User::class, 'project_user');
    }

    /**
     * Issues relationship.
     *
     * A project may have many issues.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function issues() {
        return $this->hasMany(Issue::class);
    }
}
