<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
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
                    'data.attributes.title' => 'required|max:100',
                    'data.attributes.description' => 'required|max:255',
                    'data.attributes.begin_date' => [
                        'date',
                        'before:data.attributes.end_date'
                    ],
                    'data.attributes.end_date' => [
                        'date',
                        'after_or_equal:data.attributes.begin_date'
                    ],
                    'data.attributes.status' => [
                        'required',
                        Rule::in(['open', 'on hold', 'resolved', 'duplicate', 'invalid', 'wontfix', 'closed'])
                    ],
                    'data.attributes.users' => [
                        'array'
                    ],
                    'data.attributes.users.*' => [
                        'exists:users,id' #tabla, columna
                    ]
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return[
                    'data.attributes.title' => 'required|max:100',
                    'data.attributes.description' => 'required|max:255',
                    'data.attributes.begin_date' => [
                        'date',
                        'before:data.attributes.end_date'
                    ],
                    'data.attributes.end_date' => [
                        'date',
                        'after_or_equal:data.attributes.begin_date'
                    ],
                    'data.attributes.status' => [
                        'required',
                        Rule::in(['open', 'on hold', 'resolved', 'duplicate', 'invalid', 'wontfix', 'closed'])
                    ],
                    'data.attributes.users' => [
                        'array'
                    ],
                    'data.attributes.users.*' => [
                        'exists:users,id'
                    ]
                ];
            }

            default:
                # code...
                break;
        }
    }
}
