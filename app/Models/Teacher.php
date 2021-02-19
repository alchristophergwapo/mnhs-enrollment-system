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

    protected $table="teachers";

    protected $fillable = [
        'name',
        'email',
        'contact',
        'student_id',
        'section_id',
    ];

    protected $guarded=[];

   
    public function section(){
        return $this->hasOne('App\Models\Section','id','section_id');
    }
}
