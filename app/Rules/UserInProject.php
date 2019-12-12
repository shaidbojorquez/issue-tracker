<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\User;
use App\Project;

class UserInProject implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    #Retorna boolean, true or false
    public function passes($attribute, $value) #Campo que esta validando, valor que le mando
    {
        $authUser = (object) auth()->user();#El usuario autenticado
        // If the validated input is project_id, we need to ensure that the auth user belongs to the project with that id.
        if ($attribute == 'data.attributes.project_id') {
            $project = Project::findOrFail($value);
            return $authUser->belongsToProject($project->id);
        }
        // If the validated input is assigned_to, we need then to ensure that the user we want to assign the issue belongs to the project
        else if ($attribute == 'data.attributes.assigned_to') {
            $userToAssign = User::findOrFail($value);#Buscamos al usuario
            return $userToAssign->belongsToProject(request()->input('data.attributes.project_id'));
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The user does not belong to the project selected.';
    }
}
