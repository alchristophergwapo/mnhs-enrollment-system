<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   // protected $table="teachers";

    protected $fillable = [
        'name',
        'email',
        'contact',
        'student_id',
        'section_id',
    ];

    // public function teacher()
    // {
    //     return $this->hasOne(Student::class);
    // }
    // public function student(){
    //     return $this->hasOne('App\Models\Student');
    // }
}
