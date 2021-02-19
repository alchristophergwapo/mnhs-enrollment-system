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
            'name' => ['required','string','max:50','unique:sections,name'],
            'capacity' => ['required','numeric'],
            'total_students'=>['nullable'],  
            'student_id'=>['nullable'],
            'teacher_id'=>['nullable'],
            'gradelevel_id'=>['nullable'],
        ];
    }
    
    public function message(){
        return [
            'name.required' =>'The section must be unique.',    
        ];
    }
}
