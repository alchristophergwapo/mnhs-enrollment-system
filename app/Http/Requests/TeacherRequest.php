<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends FormRequest
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
        //'unique:teachers,name'
        return [
            'teacher_name' => ['required', 'string', 'min:4', 'max:100', 'regex:/^[a-zA-Z\s.-]+$/', 'unique:teachers.teacher_name'],
            'email' => ['required', 'email:rfc,dns', 'max:50'],
            'contact' => ['required', 'string', 'max:11', 'digits:11'],
            'student_id' => ['nullable'],
            'section_id' => ['nullable'],
        ];
    }

    public function messages()
    {
        return [
            'teacher_name.required' => 'Fullname is required.',
            'teacher_name.unique' =>
            'The fullname of the teacher must be unique.',
        ];
    }
}
