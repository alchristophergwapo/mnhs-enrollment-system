<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $casts = [
        'students_id'=> 'array',
    ];

    protected $fillable = [
        'name',
        'capacity',
        'total_students',
        'teacher_id',
        'student_id',
        'gradelevel_id'
    ];

    public function gradelevel(){
        return $this->hasOne('App\Models\GradeLevel','id','gradelevel_id');
    }
    
}
