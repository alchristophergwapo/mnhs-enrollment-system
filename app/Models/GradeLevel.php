<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeLevel extends Model
{
    use HasFactory;

    protected $casts = [
        'sections'=> 'array',
        'students'=> 'array'
    ];

    protected $fillable = [
        'sections',
        'students',
        'grade_level',
        'students'
    ];

    public function sections(){
        return $this->hasMany('App\Models\Section','gradelevel_id','id');
    }
}
