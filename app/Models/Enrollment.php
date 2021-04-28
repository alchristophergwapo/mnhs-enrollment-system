<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'enrollment_status',
        'start_school_year',
        'end_school_year',
        'grade_level',
        'student_id',
        'card_image',
        'remark',
        'student_section'
    ];

    public function student()
    {
        return $this->hasOne('App\Models\Student', 'id', 'student_id');
    }

    public function section()
    {
        return $this->hasOne('App\Models\Section', 'name', 'student_section');
    }
}
