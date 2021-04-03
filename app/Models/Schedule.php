<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'section_id',
        'subject_id',
        'day',
        'start_time',
        'end_time',
        'teacher_id',
    ];

    public function section()
    {
        return belongsTo('App\Models\Section', 'id', 'section_id');
    }
}
