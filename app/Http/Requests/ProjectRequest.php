<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
                        'date'
                    ],
                    'data.attributes.end_date' => [
                        'date'
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
                    'data.attributes.title' => 'required|max:100',
                    'data.attributes.description' => 'required|max:255',
                    'data.attributes.begin_date' => [
                        'date'
                    ],
                    'data.attributes.end_date' => [
                        'date'
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
