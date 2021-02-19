<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\SeniorHigh;
use App\Models\Transferee;

use App\Http\Requests\StudentEnrollmentRequest;
use App\Http\Requests\TransfereeEnrollmentRequest;
use App\Http\Requests\SeniorHighEnrollmentRequest;
use App\Http\Requests\EnrollmentRequest;

class EnrollmentController extends Controller
{
    public function addStudent(StudentEnrollmentRequest $request) {
        $validated = $request->validated();

        if ($validated) {
            try {
                \DB::beginTransaction();

                $student = Student::create(
                    [
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
                ]
            );

                \DB::commit();

                return response()->json(['success' => 'Student added succesfully', 'student_id' => $student->id],200);

            } catch (\Exception $e){
                \DB::rollback();

                return response()->json(["error"=>$e],500);
            }
        }
    }

    public function addTransferee(TransfereeEnrollmentRequest $request) {
        $validated = $request->validated();
        if ($validated) {
            try {
                \DB::beginTransaction();
    
                Transferee::create([
                    'student_id' => $request->student_id,
                    'last_grade_completed' => $request->last_grade_completed,
                    'last_year_completed' => $request->last_year_completed,
                    'last_school_attended' => $request->last_school_attended,
                    'last_school_ID' => $request->last_school_ID,
                    'last_school_address' => $request->last_school_address,
                ]);

                \DB::commit();

                return response()->json(['success' => 'Student added succesfully'],200);
                
            } catch (\Exception $e) {
                \DB::rollback();
                return response()->json(['error'=>$e],500);
            }
        }
    }

    public function addSeniorHigh(SeniorHighEnrollmentRequest $request) {
        $validated = $request->validated();

        if ($validated) {
            try {
                \DB::beginTransaction();

                SeniorHigh::create([
                    'student_id' => $request->student_id,
                    'semester' => $request->semester,
                    'track' => $request->track,
                    'strand' => $request->strand,
                ]);

                \DB::commit();

                return response()->json(['success' => 'Student added succesfully'],200);
            } catch (\Exception $e) {
                \DB::rollback();

                return response()->json(['error'=>$e],500);
            }
        }
    }

    public function addEnrollment(EnrollmentRequest $request) {

        $validated = $request->validated();
        
        if ($validated) {
            try {
                \DB::beginTransaction();

                $imageName = $request->card_image->getClientOriginalName();
    
                Enrollment::create([
                    'enrollment_status' => $request->enrollment_status,
                    'student_id' => $request->student_id,
                    'card_image' => $imageName
                ]);

                $request->card_image->move(public_path('images'), $imageName);
    
                \DB::commit();
    
                return response()->json(['success'=>'Enrollment successfully added'],200);
            } catch (\Exception $e) {
                \DB::rollback();
    
                return response()->json(["error"=>$e->getMessage()],500);
            }
        }
    }

    public function allPendingStudents(){
        $pendingEnrollment = Enrollment::where('enrollment_status','Pending')->with('student')->get();
        return response()->json(['pendingEnrollment'=>$pendingEnrollment]);
    }

    public function allEnrollendStudents() {
        $approvedEnrollment = Enrollment::where('enrollment_status','Approved')->with('student')->get();

        return response()->json(['approvedEnrollment'=>$approvedEnrollment]);
    }

    public function approveEnrollment($id) {
        try {
            \DB::beginTransaction();

            $enrollment = Enrollment::find($id)->update([
                'enrollment_status' => "Approved"
            ]);

            $student = Student::where('id',$enrollment->student_id);

            User::create([
                'user_type' => 'student',
                'username' => $student->LRN,
                'password' => $student->lastname.$student->LRN,
            ]);

            \DB::commit();

            return response()->json(['success' => 'Enrollment approved']);
        } catch (\Exception $e) {
            \DB::rollback();

            return response()->json(['error'=>$e->getMessage()],500);
        }
    }

    public function declineEnrollment($id) {
        try {
            \DB::beginTransaction();

            Enrollment::find($id)->update([
                'enrollment_status' => "Declined"
            ]);

            \DB::commit();

            return response()->json(['success' => 'Enrollment declined']);
        } catch (\Exception $e) {
            \DB::rollback();

            return response()->json(['error'=>$e->getMessage()],500);
        }
    }
}
