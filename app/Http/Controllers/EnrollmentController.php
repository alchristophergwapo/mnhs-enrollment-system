<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

use App\Notifications\StudentEnrollmentNotification;

use App\Events\StudentEnrollEvent;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\SeniorHigh;
use App\Models\Transferee;
use App\Models\User;
use App\Models\Section;
use App\Models\GradeLevel;

use App\Http\Requests\StudentEnrollmentRequest;
use App\Http\Requests\TransfereeEnrollmentRequest;
use App\Http\Requests\SeniorHighEnrollmentRequest;
use App\Http\Requests\EnrollmentRequest;

use Carbon\Carbon;
class EnrollmentController extends Controller
{
    public function addStudent(StudentEnrollmentRequest $request) {
        $validated = $request->validated();

        if ($validated) {
            try {
                $enrollmentSubmitted = Student::query()
                    ->where([
                        ['LRN', '=', $request->LRN],
                        ['grade_level', '<=', $request->grade_level],
                        ['firstname', '=', $request->firstname],
                        ['middlename', '=', $request->middlename],
                        ['lastname', '=', $request->lastname]
                    ])
                    ->with(['enrollment'=> function($query){
                        $query->where('start_school_year', '=', Carbon::now()->format('Y'));
                    }])->orderBy('id', 'desc')->first();

                $passEnrollment = Student::query()
                    ->where([
                        ['LRN', '=', $request->LRN],
                        ['grade_level', '=', $request->grade_level],
                        ['firstname', '=', $request->firstname],
                        ['middlename', '=', $request->middlename],
                        ['lastname', '=', $request->lastname]
                    ])
                    ->with(['enrollment'=> function($query){
                        $query->where('start_school_year', '<', Carbon::now()->format('Y'));
                    }])->orderBy('id', 'desc')->first(); 
                    
                if ($enrollmentSubmitted) {
                    return response(['error' => "You have already submitted an enrollment", 'currentEnrollment' => $enrollmentSubmitted], 406);
                }

                elseif ($passEnrollment) {
                    return response([
                        'error' => "You have already submitted an enrollment/enrolled for grade ".$request->grade_level." last school year ".$passEnrollment->enrollment->start_school_year. '-'.$passEnrollment->enrollment->end_school_year,
                        'passEnrollment' => $passEnrollment
                    ], 406);
                } else {
                    \DB::beginTransaction();
                    $student = Student::create([
                        'grade_level' => $request->grade_level,
                        'PSA' => $request->PSA,
                        'LRN' => $request->LRN,
                        'average' =>  $request->average,
                        'firstname' => $request->firstname,
                        'middlename' => $request->middlename,
                        'lastname' => $request->lastname,
                        'birthdate' => $request->birthdate,
                        'age' => $request->age,
                        'gender' => $request->gender,
                        'IP' => $request->IP,
                        'IP_community' => $request->IP_Community,
                        'mother_tongue' => $request->mother_tongue,
                        'contact' => $request->contact,
                        'address' => $request->address,
                        'zipcode' => $request->zipcode,
                        'father' => $request->father,
                        'mother' => $request->mother,
                        'guardian' => $request->guardian,
                        'parent_number' => $request->parent_number,
                    ]);
    
                    if ($request->isSeniorHigh) {
                        $request->validate([
                            'semester' => [
                                'required'
                            ],
                            'track' => [
                                'required'
                            ],
                            'strand' => [
                                'required'
                            ],
                        ]);
                        SeniorHigh::create([
                            'student_id' => $student->id,
                            'semester' => $request->semester,
                            'track' => $request->track,
                            'strand' => $request->strand,
                        ]);
                    }
    
                    if ($request->isBalikOrTransfer) {
                        $request->validate([
                            'last_grade_completed' => [
                                'required',
                                'integer',
                                'min:7',
                                'max:12'
                            ],
                            'last_year_completed' => [
                                'required'
                            ],
                            'last_school_attended' => [
                                'required',
                                'min:8'
                            ],
                            'last_school_ID' => [
                                'required'
                            ],
                            'last_school_address' => [
                                'required',
                                'min:8'
                            ]
                        ]);
                        Transferee::create([
                            'student_id' => $student->id,
                            'last_grade_completed' => $request->last_grade_completed,
                            'last_year_completed' => $request->last_year_completed,
                            'last_school_attended' => $request->last_school_attended,
                            'last_school_ID' => $request->last_school_ID,
                            'last_school_address' => $request->last_school_address,
                        ]);
                    }
    
                    $imageName = $request->card_image->getClientOriginalName();
    
                    $enrollment = Enrollment::create([
                        'start_school_year' =>Carbon::now()->format('Y'),
                        'end_school_year' =>Carbon::now()->format('Y')+1,
                        'enrollment_status' => $request->enrollment_status,
                        'student_id' => $student->id,
                        'card_image' => $imageName,
                    ]);
    
    
                    $request->card_image->move(public_path('images'), $imageName);
    
                    $admin = User::where('username', 'admin')->first();
                    
                    $notif =Student::with('enrollment')
                        ->where('id', '=', $student->id)
                        ->first();
                    Notification::send($admin, new StudentEnrollmentNotification($notif));
    
                    broadcast(new StudentEnrollEvent($student, $admin));
    
                    \DB::commit();

                    $admin->load('notifications');
    
                    return response([
                        'success' => 'Student added succesfully',
                        "student"=>$notif, 
                        'admin'=>$admin
                    ]);
                    // return response(['student' => $notif]);
                // }
                }

            } catch (\Exception $e){
                \DB::rollback();

                return response()->json(["error"=>$e->getMessage()],500);
            }
        }
    }

    public function allPendingStudents(){
        $pendingEnrollment = Enrollment::where('enrollment_status','Pending')->with('student')->get();
        return response()->json(['pendingEnrollment'=>$pendingEnrollment]);
    }

    public function allEnrolledStudents() {
        $approvedEnrollment = Enrollment::where('enrollment_status','Approved')->with('student')->orderByDesc('id')->get();
        return response()->json(['approvedEnrollment'=>$approvedEnrollment]);
    }

    public function allDeclinedStudents() {
        $declinedEnrollments = Enrollment::where('enrollment_status','Declined')->with('student')->orderByDesc('id')->get();

        return response()->json(['declinedEnrollment' => $declinedEnrollments]);
    }

    public function approveEnrollment(Request $request, $id) {
        $request->validate([
            'student_section'=>'required',
            ]);
        try{
            \DB::beginTransaction();
            $enrollment = Enrollment::where('id', '=', $id)->with('student')->first();
            $student = Student::where('id', $enrollment->student_id)->with('enrollment')->first();
            $section = Section::where('name',$request->student_section)->first();

            if ($enrollment->enrollment_status == 'Pending') {
                if ($section != null && $section->total_students < $section->capacity) {
                    $section->total_students += 1;
                    $section->save();
                    User::updateOrCreate([
                        'user_type' => 'student',
                        'username' => $student->LRN,
                        'password' => \Hash::make($student->lastname.$student->LRN),
                    ]);
                    $enrollment->update([
                       'enrollment_status' => 'Approved',
                       'student_section' => $request->student_section 
                    ]);
    
                    \DB::commit();
                    
                    return response()->json(['message' => 'Enrollment approved', 'student' => $enrollment],200);
    
                } if($section != null && $section->total_students >= $section->capacity) {
                    return response()->json(['message' => $request->section.' capacity is full. Please select another section or update max capacity'],400);
                } if($section == null) {
                    return response()->json(['message' => $request->section.' cannot be found on the database. It may be deleted or have been modified.'],404);
                }
            } else {
                return response()->json(['message' => 'Enrollment approved', 'student' => $enrollment],200);
            }

        } catch (\Exception $e) {
            \DB::rollback();

            return response()->json(['error'=>$e->getMessage()],500);
        }
    }

    public function declineEnrollment($id) {
        try {
            \DB::beginTransaction();
            Enrollment::findOrFail($id)->update(['enrollment_status' => "Declined" ]);
           // Enrollment::where('student_id', '=',$id)->update(['enrollment_status' => "Declined" ]);
            \DB::commit();

            return response()->json(['success' => 'Enrollment declined']);
        } catch (\Exception $e) {
            \DB::rollback();

            return response()->json(['error'=>$e->getMessage()],500);
        }
    }

    
}