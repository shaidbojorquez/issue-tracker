<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'description', 'type', 'priority', 'status', 'project_id'];

    protected $appends = ['creator', 'assigned_to'];

    public static function boot() {
        parent::boot();

        static::deleting(function($issue) { // before delete() method call this
             $issue->users()->detach();
        });
    }

    /**
     * Doc relationship.
     *
     * A issue may have many documents related.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function docs()
    {
        return $this->morphMany(Doc::class, 'docable');
    }

    /**
     * Project relationship.
     *
     * A issue belongs to a project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function project() {
        return $this->belongsTo(Project::class);
    }

    /**
     * Users relationship.
     *
     * A issue may have one or more users.
     * Initially, one is the creator of the issue and another one which it is assigned.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function users() {
        return $this->belongsToMany(User::class, 'issue_user', 'issue_id', 'user_id')->withPivot('type');
    }

    /**
     * Creator appended attribute.
     *
     * Gets the user that created the issue.
     *
     **/
    public function getCreatorAttribute() {
        return $this->users()->wherePivot('type', 'creator')->first();
    }

    /**
     * Assigned To appended attribute.
     *
     * Gets the assigned to user.
     *
     **/
    public function getAssignedToAttribute() {
        return $this->users()->wherePivot('type', 'assigned')->first();
    }

    /**
     * Sets the creator of the issue.
     *
     * @param int $user_id
     *
     **/
    public function setCreator($user_id)
    {
        $currentCreator = $this->creator;

        if (!empty($currentCreator)) {
            $this->users()->detach($currentCreator->id);
        }

        $this->users()->attach($user_id, ['type' => 'creator']);
    }

    /**
     * Sets the assigned user of the issue.
     *
     * @param int $user_id
     *
     **/
    public function setAssignedTo($user_id)
    {
        $currentAssignedTo = $this->assigned_to;
        $currentCreator = $this->creator;

        if (!empty($currentAssignedTo)) {
            $this->users()->detach($currentAssignedTo->id);
        }

        $this->users()->attach($user_id, ['type' => 'assigned']);

        // Reset creator.
        if (!empty($currentCreator)) {
            if (!empty($currentAssignedTo) && $currentAssignedTo->id == $currentCreator->id) { #Yo lo creo y yo me asigno a el
                $this->setCreator($currentCreator->id);
            }
        } else {
            // If the issue's creator is missing, we set it to the current auth user.
            $this->setCreator(auth()->user()->id);
        }

    }
}
