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
        return [
            'teacher_name' => ['required', 'string', 'min:2', 'max:100', "regex:/^[a-zA-Z\s.-Ññ']+$/", 'unique:teachers,teacher_name'],
            'email' => ['required', 'email:rfc,dns', 'max:100'],
            'contact' => ['required', 'string', 'max:11', 'digits:11'],
            'student_id' => ['nullable'],
            'section_id' => ['nullable'],
        ];
    }

    public function messages()
    {
        return [
            'teacher_name.required' => 'The teacher name field is required!',
            'email.required' => 'The email field is required!',
            'contact.required' => 'The contact field is required!',
            'teacher_name.unique' => 'The fullname of the teacher must be unique!',
        ];
    }
}
