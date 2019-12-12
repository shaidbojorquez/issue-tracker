<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Project relationship
     *
     * A user may belongs to one or more project
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function projects() {
        return $this->belongsToMany(Project::class, 'project_user');
    }

    /**
     * Issues relationship.
     *
     * A issue may have one or more users.
     * Initially, one is the creator of the issue and another one which it is assigned.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function issues() {
        return $this->belongsToMany(Issue::class, 'issue_user');
    }

    /**
     * Checks if the user is associated with a project.
     *
     * This function checks if a project id match with the user's projects pool.
     *
     * @return boolean
     **/
    public function belongsToProject($project_id) {
        return $this->projects->where('id', $project_id)->count() > 0; #Retorna true or false
    }
}
