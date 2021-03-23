<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransfereeEnrollmentRequest extends FormRequest
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
            'student_id' => [
                'required'
            ],
            'last_grade_completed' => [
                'required',
                'min:7',
                'max:12'
            ],
            'last_year_completed' => [
                'required'
            ],
            'last_school_attended' => [
                'required',
                'min:8'
            ],
            'last_school_ID' => [
                'required'
            ],
            'last_school_address' => [
                'required',
                'min:8'
            ],
        ];
    }
}
