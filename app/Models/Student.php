<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = [
        'grade_level',
        'PSA',
        'LRN',
        'average',
        'firstname',
        'middlename',
        'lastname',
        'birthdate',
        'age',
        'gender',
        'IP',
        'IP_community',
        'mother_tongue',
        'contact',
        'address',
        'zipcode',
        'father',
        'mother',
        'guardian',
        'parent_number',
    ];

    // public function teacher(){
    //     return $this->belongsTo('App\Models\Teacher');
    // }
    public function enrollment() {
        return $this->hasOne('App\Models\Enrollment','student_id','id');
    }
}
