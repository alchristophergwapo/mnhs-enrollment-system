<?php

namespace App\Models;

use App\Events\StudentEnrollEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;


class Student extends Model
{
    use HasFactory;
    // protected $guarded = [];

    // protected $dispatchesEvents = [

    //     'created' => StudentEnrollEvent::class,

    // ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
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
    public function enrollment()
    {
        return $this->hasOne('App\Models\Enrollment', 'student_id', 'id');
    }

    // public static function boot() {
    //     parent::boot();

    //     static::created(function($model) {
    //         $admin = User::where('username', 'admin')->first();

    //         // Notification::send($admin, new StudentEnrollmentNotification($model));
    //     });
    // }
}
