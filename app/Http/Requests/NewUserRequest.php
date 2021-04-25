<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewUserRequest extends FormRequest
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
        return [
            'username' => [
                'required',
                'min:6',
                'max:20',
                'regex:/^[a-zA-Z0-9_]+$/u'
            ],
            'password' => [
                'required',
                'min:8',
                'max:20'
            ],
            'user_type' => [
                'required',
            ]
        ];
    }
}
