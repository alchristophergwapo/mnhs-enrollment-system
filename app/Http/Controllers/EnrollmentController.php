<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

                Enrollment::create([
                    'start_school_year' =>Carbon::now()->format('Y'),
                    'end_school_year' =>Carbon::now()->format('Y')+1,
                    'enrollment_status' => $request->enrollment_status,
                    'student_id' => $student->id,
                    'card_image' => $imageName,
                ]);


                $request->card_image->move(public_path('images'), $imageName);

                \DB::commit();

                return response()->json(['success' => 'Student added succesfully'],200);

            } catch (\Exception $e){
                \DB::rollback();

                return response()->json(["error"=>$e],500);
            }
        }
    }

    public function allPendingStudents(){
        $pendingEnrollment = Enrollment::where('enrollment_status','Pending')->with('student')->get();
        return response()->json(['pendingEnrollment'=>$pendingEnrollment]);
    }

    public function allEnrolledStudents() {
        $approvedEnrollment = Enrollment::where('enrollment_status','Approved')->with('student')->get();
        return response()->json(['approvedEnrollment'=>$approvedEnrollment]);
    }

    public function allDeclinedStudents() {
        $declinedEnrollments = Enrollment::where('enrollment_status','Declined')->with('student')->get();

        return response()->json(['declinedEnrollment' => $declinedEnrollments],200);
    }

    public function approveEnrollment(Request $request, $id) {
        $request->validate([
            'student_section'=>'required',
            ]);
        try{
            \DB::beginTransaction();
            $section=$request->student_section;
           // foreach($request->student_section as $name){$section=$name;}
            $enrollment = Enrollment::find($id)->get();

            $enrollment = Enrollment::where('id', $id)->get();

            $student = Student::where('id', $enrollment[0]->student_id)->get();

            User::updateOrCreate([
                'user_type' => 'student',
                'username' => $student[0]->LRN,
                'password' => \Hash::make($student[0]->lastname.$student[0]->LRN),
                'remember_token' => \Str::random(10),
            ]);

            Enrollment::find($id)->update([
                'enrollment_status' => 'Approved',
                'student_section' =>$section
            ]);
            \DB::commit();
            return response()->json(['success' => 'Enrollment approved']);
            $section = Section::where('name',$request->section)->get();

            if (count($section) > 0 && $section[0]->total_students < $section[0]->capacity) {
                $section[0]->total_students += 1;
                $section[0]->save();
                User::updateOrCreate([
                    'user_type' => 'student',
                    'username' => $student[0]->LRN,
                    'password' => \Hash::make($student[0]->lastname.$student[0]->LRN),
                ]);
               Enrollment::find($id)->update(['enrollment_status' => 'Approved','student_section' => $request->student_section ]);
               // Enrollment::where('student_id', '=',$id)->update(['enrollment_status' => 'Approved','student_section' => $request->student_section]);
            } if(count($section) > 0 && $section[0]->total_students >= $section[0]->capacity) {
                return response()->json(['message' => $request->section.' capacity is full. Please select another section or update max capacity'],400);
            } if(count($section) == 0) {
                return response()->json(['message' => $request->section.' cannot be found on the database. It may be deleted or have been modified.'],404);
            }

            \DB::commit();

            return response()->json(['success' => 'Enrollment approved'],200);
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



//Selected Section For A Gradelevel In EnrollmenData.Vue
  public function selectedGradeForSection($id){
    try{
        $array=[];
        $list=Section::cursor();
        foreach($list  as $sec){
            $gradeLevel=GradeLevel::where('id','=',$sec->gradelevel_id)->first();
            if($gradeLevel->grade_level==$id){
                $sec->name="Gr. ".$gradeLevel->grade_level." --- ".$sec->name;   
                array_push($array,$sec->makeHidden(['id','teacher_id','gradelevel','total_students','capacity','gradelevel_id','students_id','created_at','updated_at']));
            }
        }

      return response()->json($array); 
        }
        catch(\Exception $e){
         return response()->json(['error'=> $e->getMessage()],500);
        } 
  }


}