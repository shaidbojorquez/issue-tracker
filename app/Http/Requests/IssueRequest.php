<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
                    ]
                ];
            }
            
            default:
                # code...
                break;
        }
    }
}
