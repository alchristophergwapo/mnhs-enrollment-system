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

    protected $table = 'teachers';

    protected $casts = [
        'students_id' => 'array',
    ];

    protected $fillable = [
        'teacher_name',
        'email',
        'contact',
        'grade_level_id',
        'section_id',
        'subjects_id',
    ];

    protected $guarded = [];

    public function section()
    {
        return $this->hasOne('App\Models\Section', 'id', 'section_id');
    }
}
