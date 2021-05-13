<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SectionRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:50', 'unique:sections,name', 'regex:/^[a-zA-Z0-9\s-.Ññ]+$/'],
            'capacity' => ['required', 'integer', 'min:0'],
            'total_students' => ['nullable'],
            'student_id' => ['nullable'],
            'teacher_id' => ['nullable'],
            'gradelevel_id' => ['nullable'],
        ];
        // this.message();
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'The section name is required.',
            'name.unique' => 'The section name is already used.',
        ];
    }
}
