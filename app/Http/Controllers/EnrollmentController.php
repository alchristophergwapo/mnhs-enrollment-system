<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

use App\Notifications\StudentEnrollmentNotification;

use App\Events\StudentEnrollEvent;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\SeniorHigh;
use App\Models\Transferee;
use App\Models\User;
use App\Models\Section;

use App\Http\Requests\StudentEnrollmentRequest;
use Carbon\Carbon;

class EnrollmentController extends Controller
{

    public function updateStudent(StudentEnrollmentRequest $request, $id)
    {
        $updated = $request->validated();
        if ($updated) {
            try {
                error_log($id);
                \DB::beginTransaction();
                Student::findOrFail($id)->update([
                    'PSA' => $request->PSA,
                    'LRN' => $request->LRN,
                    'average' => (int)$request->average,
                    'firstname' => $request->firstname,
                    'middlename' => $request->middlename,
                    'lastname' => $request->lastname,
                    'birthdate' => $request->birthdate,
                    'age' => (int)$request->age,
                    'gender' => $request->gender,
                    'IP' => $request->IP,
                    'IP_community' => $request->IP_community,
                    'mother_tongue' => $request->mother_tongue,
                    'contact' => $request->contact,
                    'address' => $request->address,
                    'zipcode' => $request->zipcode,
                    'father' => $request->father,
                    'mother' => $request->mother,
                    'guardian' => $request->guardian,
                    'parent_number' => $request->parent_number,
                ]);
                \DB::commit();
                return response()->json(['updated' => 'Student updated succesfully'], 200);
            } catch (\Exception $e) {
                \DB::rollback();
                return response()->json(["error" => $e], 500);
            }
        }
    }

    public function addStudent(StudentEnrollmentRequest $request)
    {
        $validated = $request->validated();
        if ($validated) {
            try {
                $grade_level = $request->grade_level;
                $enrollmentSubmitted = Student::query()
                    ->where([
                        ['LRN', '=', $request->LRN],
                    ])
                    ->with([
                        'enrollment' => function ($query) {
                            $query->where(
                                'start_school_year',
                                '=',
                                Carbon::now()->format('Y')
                            );
                        },
                    ])
                    ->orderBy('id', 'desc')
                    ->first();

                $passEnrollment = Student::query()
                    ->where([
                        ['LRN', '=', $request->LRN],
                    ])
                    ->with([
                        'enrollment' => function ($query) {
                            $query->where(
                                'start_school_year',
                                '<',
                                Carbon::now()->format('Y')
                            );
                        },
                    ])
                    ->orderBy('id', 'desc')
                    ->first();

                if ($enrollmentSubmitted && $enrollmentSubmitted->enrollment) {
                    return response(
                        [
                            'error' =>
                            'You have already submitted an enrollment for grade ' . $enrollmentSubmitted->enrollment->grade_level . ' this school year (' . $enrollmentSubmitted->enrollment->start_school_year . '-' . $enrollmentSubmitted->enrollment->end_school_year . ')',
                            'currentEnrollment' => $enrollmentSubmitted,
                        ],
                        406
                    );
                } elseif ($passEnrollment && $passEnrollment->enrollment && $passEnrollment->enrollment->grade_level == $request->grade_level) {
                    return response(
                        [
                            'error' =>
                            'You have already submitted an enrollment/enrolled for grade ' .
                                $passEnrollment->enrollment->grade_level .
                                ' last school year ' .
                                $passEnrollment->enrollment->start_school_year .
                                '-' .
                                $passEnrollment->enrollment->end_school_year . '. You can only enroll to grade ' . ($passEnrollment->enrollment->grade_level + 1) . '. If this is a mistake, please contact the school enrollment personnel. Thank you!',
                            'passEnrollment' => $passEnrollment,
                        ],
                        406
                    );
                } else {
                    \DB::beginTransaction();
                    $student = Student::create([
                        'PSA' => $request->PSA,
                        'LRN' => $request->LRN,
                        'average' => (int)$request->average,
                        'firstname' => $request->firstname,
                        'middlename' => $request->middlename,
                        'lastname' => $request->lastname,
                        'birthdate' => $request->birthdate,
                        'age' => (int)$request->age,
                        'gender' => $request->gender,
                        'IP' => $request->IP,
                        'IP_community' => $request->IP_community,
                        'mother_tongue' => $request->mother_tongue,
                        'contact' => $request->contact,
                        'address' => $request->address,
                        'zipcode' => $request->zipcode,
                        'father' => $request->father,
                        'mother' => $request->mother,
                        'guardian' => $request->guardian,
                        'parent_number' => $request->parent_number
                    ]);

                    if ($request->isSeniorHigh == 'true') {
                        $request->validate([
                            'semester' => ['required'],
                            'track' => ['required'],
                            'strand' => ['required'],
                        ]);
                        SeniorHigh::create([
                            'student_id' => (int)$student->id,
                            'semester' => $request->semester,
                            'track' => $request->track,
                            'strand' => $request->strand,
                        ]);
                    }

                    if ($request->isBalikOrTransfer == 'true') {
                        $request->validate([
                            'last_grade_completed' => [
                                'required',
                                'integer',
                                'min:6',
                                'max:12',
                            ],
                            'last_year_completed' => ['required'],
                            'last_school_attended' => ['required', 'min:8'],
                            'last_school_ID' => ['required'],
                            'last_school_address' => ['required', 'min:8'],
                        ]);
                        Transferee::create([
                            'student_id' => $student->id,
                            'last_grade_completed' =>
                            $request->last_grade_completed,
                            'last_year_completed' =>
                            $request->last_year_completed,
                            'last_school_attended' =>
                            $request->last_school_attended,
                            'last_school_ID' => $request->last_school_ID,
                            'last_school_address' =>
                            $request->last_school_address,
                        ]);
                    }

                    $imageName = $request->card_image->getClientOriginalName();

                    Enrollment::create([
                        'grade_level' => (int)$request->grade_level,
                        'start_school_year' => Carbon::now()->format('Y'),
                        'end_school_year' => Carbon::now()->format('Y') + 1,
                        'enrollment_status' => $request->enrollment_status,
                        'student_id' => $student->id,
                        'card_image' => $imageName,
                    ]);

                    $admin = User::where('user_type', 'admin')->first();
                    $teacher_admin = User::where('user_type', 'teacher_admin')->get();

                    $notif = Student::with('enrollment')
                        ->where('id', '=', (int)$student->id)
                        ->first();
                    try {
                        Notification::send(
                            $admin,
                            new StudentEnrollmentNotification($notif)
                        );

                        event(new StudentEnrollEvent($student, $admin));
                        foreach ($teacher_admin as $tAdd) {
                            $teacher_admin_gLevel = explode('_', $tAdd->username)[1];
                            if ($teacher_admin_gLevel == $request->grade_level) {
                                Notification::send($tAdd, new StudentEnrollmentNotification($notif));
                                event(new StudentEnrollEvent($student, $tAdd));
                            }
                        }
                    } catch (\Exception $e) {
                        \Log::error(get_class() . ' pusher event ' . $e);
                    }

                    \DB::commit();

                    $request->card_image->move(
                        public_path('images'),
                        $imageName
                    );

                    $admin->load('notifications');

                    return response([
                        'success' => 'Enrollment submitted.',
                        'student' => $notif,
                        'admin' => $admin,
                    ]);
                }
            } catch (\Exception $e) {
                \DB::rollback();
                \Log::error(get_class() . 'pusher event');
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
    }

    public function studentSectionDetails($section)
    {
        $classmates = Enrollment::where('student_section', '=', $section)
            ->join('students', 'enrollments.student_id', 'students.id')
            ->select(
                'enrollments.id as enrollment_id',
                'enrollments.grade_level',
                'students.firstname',
                'students.middlename',
                'students.lastname',
                'students.address'
            )
            ->get();
        foreach ($classmates as  $value) {
            $value->middlename .= " " . $value->firstname . " " . $value->lastname;
        }
        return response()->json(['classmates' => $classmates]);
    }

    public function allPendingStudents($adminLevel = null)
    {
        // return response($adminLevel);
        if ($adminLevel != 'null') {
            $pendingEnrollment = Enrollment::where('enrollment_status', 'Pending')
                ->where('grade_level', (int)$adminLevel)
                ->join('students', 'enrollments.student_id', 'students.id')
                ->select('students.*', 'enrollments.*')
                ->get();

            $sort = $pendingEnrollment->sortBy('average', 1, true);
            $sorted = $sort->values()->all();
            return response()->json(['pendingEnrollment' => $sorted]);
        }
        $pendingEnrollment = Enrollment::where('enrollment_status', '=', 'Pending')
            ->join('students', 'enrollments.student_id', 'students.id')
            ->select('students.*', 'enrollments.*')
            ->get();

        $sort = $pendingEnrollment->sortBy('average', 1, true);
        $sorted = $sort->values()->all();
        return response()->json(['pendingEnrollment' => $sorted]);
    }

    public function allEnrolledStudents($gradeLevel = null)
    {
        $approvedEnrollment = [];
        if ($gradeLevel == 'null') {
            $approvedEnrollment = Enrollment::where('enrollment_status', 'Approved')
                ->leftJoin('students', 'enrollments.student_id', 'students.id')
                ->leftJoin('sections', 'enrollments.student_section', 'sections.id')
                ->select('enrollments.*', 'students.*', 'sections.name as section_name')
                ->get();
        } else {
            $approvedEnrollment = Enrollment::where('enrollment_status', 'Approved')
                ->where('grade_level', (int)$gradeLevel)
                ->leftJoin('students', 'enrollments.student_id', 'students.id')
                ->leftJoin('sections', 'enrollments.student_section', 'sections.id')
                ->select('enrollments.*', 'students.*', 'sections.name as section_name')
                ->get();
        }
        return response()->json(['approvedEnrollment' => $approvedEnrollment]);
    }

    public function allDeclinedStudents($adminLevel)
    {
        $declinedEnrollments = null;

        if (!$adminLevel) {
            $declinedEnrollments = Enrollment::where(
                'enrollment_status',
                'Declined'
            )
                ->where('grade_level', $adminLevel)
                ->join('students', 'enrollments.student_id', 'students.id')
                ->select('students.*', 'enrollments.*')
                ->get();
        } else {
            $declinedEnrollments = Enrollment::where(
                'enrollment_status',
                'Declined'
            )
                ->join('students', 'enrollments.student_id', 'students.id')
                ->select('students.*', 'enrollments.*')
                ->get();
        }

        $sort = $declinedEnrollments->sortBy('average', 1, true);
        $sorted = $sort->values()->all();
        return response()->json(['declinedEnrollment' => $sorted]);
    }


    public function approveEnrollment(Request $request, $id)
    {
        $request->validate([
            'student_section' => 'required',
        ]);
        try {
            // error_log($request->student_section);
            \DB::beginTransaction();
            $enrollment = Enrollment::where('id', '=', (int)$id)
                ->with('student')
                ->first();
            $student = Student::where('id', $enrollment->student_id)
                ->with('enrollment')
                ->first();
            $section = Section::where(
                'name',
                $request->student_section
            )->first();

            if ($enrollment->enrollment_status == 'Pending') {
                if (
                    $section != null &&
                    $section->total_students < $section->capacity
                ) {
                    $section->total_students += 1;
                    $section->save();
                    $user = User::updateOrCreate([
                        'user_type' => 'student',
                        'username' => $student->LRN,
                        'password' => \Hash::make(
                            $student->lastname . $student->LRN
                        ),
                    ]);
                    error_log("id:" . $id);
                    $enrollment->update([
                        'enrollment_status' => 'Approved',
                        'student_section' => $section->id,
                    ]);
                    \DB::commit();

                    return response()->json(
                        [
                            'message' => 'Enrollment approved',
                            'student' => $enrollment,
                        ],
                        200
                    );
                }
                if (
                    $section != null &&
                    $section->total_students >= $section->capacity
                ) {
                    return response()->json(
                        [
                            'message' =>
                            $request->section .
                                $section->name . ' is full. Please select another section or update max capacity',
                        ],
                        400
                    );
                }
                if ($section == null) {
                    return response()->json(
                        [
                            'message' =>
                            $request->section .
                                ' cannot be found on the database. It may be deleted or have been modified.',
                        ],
                        404
                    );
                }
            }
            // FOR  APPROVING THE STUDENT AFTER IT WAS RECHECK AND FIXED WHY IT IS DECLINE
            else {
                if (
                    $section != null &&
                    $section->total_students < $section->capacity
                ) {
                    $section->total_students += 1;
                    $section->save();
                    User::updateOrCreate([
                        'user_type' => 'student',
                        'username' => $student->LRN,
                        'password' => \Hash::make(
                            $student->lastname . $student->LRN
                        ),
                    ]);
                    error_log("id:" . $id);
                    $enrollment->update([
                        'enrollment_status' => 'Approved',
                        'remark' => null,
                        'student_section' => $section->id,
                    ]);
                    \DB::commit();

                    return response()->json(
                        [
                            'message' => 'Enrollment approved',
                            'student' => $enrollment,
                        ],
                        200
                    );
                }
                if (
                    $section != null &&
                    $section->total_students >= $section->capacity
                ) {
                    return response()->json(
                        [
                            'message' =>
                            $request->section .
                                $section->name . ' is full. Please select another section or update max capacity',
                        ],
                        400
                    );
                }
                if ($section == null) {
                    return response()->json(
                        [
                            'message' =>
                            $request->section .
                                ' cannot be found on the database. It may be deleted or have been modified.',
                        ],
                        404
                    );
                }
            }
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function declineEnrollment(Request $request, $id)
    {
        try {
            \DB::beginTransaction();
            Enrollment::where('student_id', '=', (int)$id)->update([
                'enrollment_status' => 'Declined',
                'remark' => $request->remarks
            ]);
            // Enrollment::where('student_id', '=',$id)->update(['enrollment_status' => "Declined" ]);
            \DB::commit();

            return response()->json(['success' => 'Enrollment declined']);
        } catch (\Exception $e) {
            \DB::rollback();

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
