<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentEnrollmentRequest extends FormRequest
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
            'average' => ['required', 'numeric', 'min:0', 'max:100'],
            'grade_level' => ['required',
                'numeric',
                'min:7',
                'max:12'
            ],
            'PSA' => 'nullable',
            'LRN' => ['required'],
            'firstname' => [
                'min:3',
                'max:50',
                'required'
            ],
            'middlename' => [
                'nullable',
                'min:3',
                'max:50',
            ],
            'lastname' => [
                'regex:/^[a-zA-Z]+$/u',
                'min:3',
                'max:50',
                'required'
            ],
            'birthdate' => [
                'required',
                'date'
            ],
            'age' => [
                'required',
                'numeric',
                'min:11',
                'max:30'
            ],
            'gender' => [
                'required',
                'min:4',
                'max:6'
            ],
            'IP' => [
                'required',
                'min:2',
                'max:3'
            ],
            'IP_community' => ['nullable'],
            'mother_tongue' => [
                'required',
                'min:4'
            ],
            'contact' => [
                'min:11',
                'max:11'
            ],
            'address' => [
                'required',
                'min:4'
            ],
            'zipcode' => [
                'required',
                'min:4',
            ],
            'father' => 'nullable',
            'mother' => 'nullable',
            'guardian' => [
                'required',
                'min:3',
                'max:50',
            ],
            'parent_number' => [
                'required',
                'min:11',
                'max:11'
            ],
        ];
    }
}
