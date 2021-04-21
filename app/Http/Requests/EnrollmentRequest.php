<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnrollmentRequest extends FormRequest
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
        'enrollment_status' => ['required'],
        'student_section'=>['required','string'],
        'start_school_year' => ['required'],
        'end_school_year' => ['required'],
        'student_id' => ['required'],
        'card_image' => [
            'required',
            'mimes:jpeg,png,jpg'
        ]
        ];
    }
}
