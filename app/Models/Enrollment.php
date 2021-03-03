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
        'student_id',
        'card_image',
        'student_section'
    ];

    public function student() {
        return $this->hasOne('App\Models\Student','id','student_id');
    }

}
