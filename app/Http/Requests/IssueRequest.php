<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\UserInProject;
use App\Rules\UserIsCreator;

class IssueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method())
        {
            case 'POST':
            {
                return [
                    'data.attributes.title' => 'max:100|required',
                    'data.attributes.type' => [
                        'required',
                        Rule::in(['bug', 'enhancement', 'proposal', 'task'])
                    ],
                    'data.attributes.priority' => [
                        'required',
                        Rule::in(['trivial', 'minor', 'major', 'critical', 'blocker'])
                    ],
                    'data.attributes.status' => [
                        'required',
                        Rule::in(['open', 'on hold', 'resolved', 'duplicate', 'invalid', 'wontfix', 'closed'])
                    ],
                    'data.attributes.project_id' => [
                        'required',
                        'exists:projects,id', #tabla, columna
                        new UserInProject()
                    ],
                    'data.attributes.assigned_to' => [
                        'required',
                        'exists:users,id',
                        new UserInProject()
                    ]
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return[
                    'data.attributes.title' => 'max:100|required',
                    'data.attributes.type' => [
                        'required',
                        Rule::in(['bug', 'enhancement', 'proposal', 'task'])
                    ],
                    'data.attributes.priority' => [
                        'required',
                        Rule::in(['trivial', 'minor', 'major', 'critical', 'blocker'])
                    ],
                    'data.attributes.status' => [
                        'required',
                        Rule::in(['open', 'on hold', 'resolved', 'duplicate', 'invalid', 'wontfix', 'closed'])
                    ],
                    'data.attributes.project_id' => [
                        'required',
                        'exists:projects,id',
                        new UserInProject()
                    ],
                    'data.attributes.assigned_to' => [
                        'required',
                        'exists:users,id',
                        new UserInProject()
                    ]
                ];
            }

            default:
                # code...
                break;
        }
    }
}
