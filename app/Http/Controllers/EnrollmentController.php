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
use App\Models\UserDetails;
use App\Notifications\StudentAdmissionNotification;
use Carbon\Carbon;

class EnrollmentController extends Controller
{
    //UPDATING THE STUDENT DETAILS IN DECLINE STUDENT AREAS
    public function updatedeclineEnrollment(StudentEnrollmentRequest $request, $id)
    {
        $updated = $request->validated();
        if ($updated) {
            try {
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

                Enrollment::where('student_id', '=', (int)$id)->update([
                    'grade_level' => $request->grade_level,
                    'specialization' => $request->specialization
                ]);

                if ($request->track != null) {
                    SeniorHigh::where('student_id', '=', (int)$id)->update([
                        'track' => $request->track,
                        'strand' => $request->strand,
                        'semester' => $request->semester,
                    ]);
                }

                if ($request->last_year_completed != null) {
                    Transferee::where('student_id', '=', (int)$id)->update([
                        'last_year_completed' => $request->last_year_completed,
                        'last_grade_completed' => $request->last_grade_completed,
                        'last_school_attended' => $request->last_school_attended,
                        'last_school_ID' => $request->last_school_ID,
                        'last_school_address' => $request->last_school_address,
                    ]);
                }

                \DB::commit();
                return response()->json(['updated' => 'Student updated succesfully'], 200);
            } catch (\Exception $e) {
                \DB::rollback();
                return response()->json(["error" => $e], 500);
            }
        }
    }
    //UPDATING THE STUDENT DATA WHO ALREADY APPROVE BY THE ADMIN
    public function updateStudent(StudentEnrollmentRequest $request, $id)
    {
        $updated = $request->validated();
        if ($updated) {
            try {
                \DB::beginTransaction();
                $enrollment = Enrollment::where('student_id', '=', (int)$id)
                    ->get();
                $section = Section::where('id', (int)($enrollment[count($enrollment) - 1]->student_section))->first();

                $newSection = Section::where('name', '=', $request->section_name)->first();

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
                if ($request->track != null) {
                    SeniorHigh::where('student_id', '=', (int)$id)->update([
                        'track' => $request->track,
                        'strand' => $request->strand,
                        'semester' => $request->semester,
                    ]);
                }

                if ($request->last_year_completed != null) {
                    Transferee::where('student_id', '=', (int)$id)->update([
                        'last_year_completed' => $request->last_year_completed,
                        'last_grade_completed' => $request->last_grade_completed,
                        'last_school_attended' => $request->last_school_attended,
                        'last_school_ID' => $request->last_school_ID,
                        'last_school_address' => $request->last_school_address,
                    ]);
                }

                $enrollment[count($enrollment) - 1]->update([
                    'student_section' => (int)$newSection->id,
                    'grade_level' => $request->grade_level,
                    'specialization' => $request->specialization
                ]);
                if ($section && $section->name != $request->section_name) {
                    //Sakto ang gradelevel then sayop ang pagbutang niya og section
                    if ($newSection->total_students === $newSection->capacity) {
                        \DB::rollBack();
                        return response()->json(['updated' => 'This section has reach its limits'], 500);
                    } else {
                        $newSection->update([
                            'total_students' => (int) $newSection->total_students + 1,
                        ]);

                        $section->update([
                            'total_students' => (int)$section->total_students - 1,
                        ]);
                    }
                }

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

                $passEnrollment = Student::query()
                    ->where([
                        ['LRN', '=', $request->LRN],
                    ])
                    ->with([
                        'enrollment' => function ($query) {
                            $query->where(
                                'start_school_year',
                                '<=',
                                Carbon::now()->format('Y')
                            );
                        },
                    ])
                    ->orderBy('id', 'desc')
                    ->first();

                if ($passEnrollment && $passEnrollment->enrollment) {
                    return response(
                        [
                            'error' =>
                            'You have already submitted an application for admission. You can only apply for admission once. If this is a mistake, please contact the school enrollment personnel. Thank you!',
                            'passEnrollment' => $passEnrollment,
                        ],
                        406
                    );
                } else {
                    \DB::beginTransaction();
                    $student = Student::create($validated);

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
                        $request->validate(
                            [
                                'last_grade_completed' =>
                                'required|integer|min:6|max:11',
                                'last_year_completed' => 'required',
                                'last_school_attended' => 'required|min:8',
                                'last_school_ID' => 'required',
                                'last_school_address' => 'required|min:4',
                            ],
                            [
                                'last_grade_completed.integer' => 'Last grade completed must be an integer.',
                                'last_year_completed.min' => 'Last year is either of the ff. [6,7,8,9,10,11].',
                                'last_school_attended.min' => 'Last school attended must be at least 8 characters.',
                                'last_school_address.min' => 'Last school address must be at least 8 characters.'
                            ]
                        );
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
                        'specialization' => $request->specialization,
                        'student_id' => $student->id,
                        'card_image' => $imageName,
                        'student_email' => $request->email,
                    ]);

                    if ($request->enrollment_status === 'Approved') {
                        $user = User::updateOrCreate([
                            'user_type' => 'student',
                            'username' => $student->LRN,
                            'password' => \Hash::make(
                                $student->lastname . $student->LRN
                            ),
                            'email' => $request->email,
                        ]);

                        UserDetails::create([
                            'user_fullname' => $student->firstname . ' ' . $student->lastname,
                            'user_id' => $user->id
                        ]);
                    }

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
                        \DB::rollback();
                        return response()->json(['error' => $e], 500);
                    }

                    \DB::commit();

                    $request->card_image->move(
                        public_path('images'),
                        $imageName
                    );

                    $admin->load('notifications');

                    return response([
                        'success' => 'Application for admission submitted.',
                        'student' => $notif,
                        'admin' => $admin,
                    ]);
                }
            } catch (\Exception $e) {
                \DB::rollback();
                \Log::error(get_class() . 'pusher event');
                return response()->json(['error' => $e], 500);
            }
        }
    }

    public function enroll(Request $request)
    {
        try {
            \DB::beginTransaction();
            $enrollmentCreated = Enrollment::create([
                'enrollment_status' => 'Approved',
                'start_school_year' => $request->start_school_year,
                'end_school_year' => $request->end_school_year,
                'grade_level' => $request->grade_level,
                'enrollment_remarks' => 'NO REMARKS',
                'specialization' => $request->specialization,
                'student_id' => $request->student_id
            ]);
            if ($request->grade_level === 11) {
                $request->validate([
                    'semester' => ['required'],
                    'track' => ['required'],
                    'strand' => ['required'],
                ]);
                SeniorHigh::create([
                    'student_id' => $request->student_id,
                    'semester' => $request->semester,
                    'track' => $request->track,
                    'strand' => $request->strand,
                ]);
            }

            if ($enrollmentCreated) {
                \DB::commit();
                return response(['success' => 'Application for admission successfully submitted!']);
            }
        } catch (\Throwable $th) {
            return response(['error' => $th], 500);
        }
    }

    public function editEnrollmentRemarks(Request $request, $id)
    {
        $enrollment = Enrollment::where('id', $id)->first();
        try {
            \DB::beginTransaction();
            if ($enrollment) {
                $update = $enrollment->update([
                    'enrollment_remarks' => $request->enrollment_remarks,
                ]);
                if ($update) {
                    \DB::commit();
                    return response(['success' => 'Student successfully marked as ' . $request->enrollment_remarks . '.', $update]);
                }
            }
        } catch (\Exception $e) {
            \DB::rollback();
            return response(['error' => $e], 500);
        }
    }

    public function allPendingStudents($adminLevel = null)
    {

        // return response($adminLevel);
        if ($adminLevel != 'null') {
            $pendingEnrollment = Enrollment::where('enrollment_status', 'Pending')
                ->where('grade_level', (int)$adminLevel)
                ->leftJoin('students', 'enrollments.student_id', 'students.id')
                ->leftJoin('senior_high', 'senior_high.student_id', 'students.id')
                ->leftJoin('transferees', 'transferees.student_id', 'students.id')
                ->select('senior_high.*', 'transferees.*', 'students.*', 'enrollments.*')
                ->get();

            $sort = $pendingEnrollment->sortBy('average', 1, true);
            $sorted = $sort->values()->all();
            return response()->json(['pendingEnrollment' => $sorted]);
        }

        $pendingEnrollment = Enrollment::where('enrollment_status', '=', 'Pending')
            ->leftJoin('students', 'enrollments.student_id', 'students.id')
            ->leftJoin('transferees', 'transferees.student_id', 'students.id')
            ->leftJoin('senior_high', 'senior_high.student_id', 'students.id')
            ->select('senior_high.*', 'transferees.*', 'students.*', 'enrollments.*')
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
                ->leftJoin('senior_high', 'senior_high.student_id', 'students.id')
                ->leftJoin('transferees', 'transferees.student_id', 'students.id')
                ->select('sections.name as section_name', 'transferees.*', 'senior_high.*', 'students.*', 'enrollments.*')
                ->get();
        } else {
            $approvedEnrollment = Enrollment::where('enrollment_status', 'Approved')
                ->where('grade_level', (int)$gradeLevel)
                ->leftJoin('students', 'enrollments.student_id', 'students.id')
                ->leftJoin('senior_high', 'senior_high.student_id', 'students.id')
                ->leftJoin('transferees', 'transferees.student_id', 'students.id')
                ->leftJoin('sections', 'enrollments.student_section', 'sections.id')
                ->select('sections.name as section_name', 'transferees.*', 'senior_high.*', 'students.*', 'enrollments.*')
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
                ->lefJoin('students', 'enrollments.student_id', 'students.id')
                ->leftJoin('transferees', 'transferees.student_id', 'students.id')
                ->leftJoin('senior_high', 'senior_high.student_id', 'students.id')
                ->select('senior_high.*', 'transferees.*', 'students.*', 'enrollments.*')
                ->get();
        } else {
            $declinedEnrollments = Enrollment::where(
                'enrollment_status',
                'Declined'
            )
                ->leftJoin('students', 'enrollments.student_id', 'students.id')
                ->leftJoin('transferees', 'transferees.student_id', 'students.id')
                ->leftJoin('senior_high', 'senior_high.student_id', 'students.id')
                ->select('senior_high.*', 'transferees.*', 'students.*', 'enrollments.*')
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

            if ($enrollment->enrollment_status != 'Approved') {
                if (
                    $section != null &&
                    $section->total_students < $section->capacity
                ) {
                    $section->total_students += 1;
                    $section->save();
                    $newUser = User::updateOrCreate([
                        'user_type' => 'student',
                        'username' => $student->LRN,
                        'password' => \Hash::make(
                            $student->lastname . $student->LRN
                        ),
                        'email' => $enrollment->student_email
                    ]);
                    $enrollment->update([
                        'enrollment_status' => 'Approved',
                        'remark' => null,
                        'student_section' => $section->id,
                    ]);

                    $additionalDetails = "Your account has been created. To check your schedules and section, you can login into your account using this link http://mnhsenrollment-frontend.herokuapp.com/sign-in . Copy or click the link to login into your account.";

                    Notification::send($newUser, new StudentAdmissionNotification($enrollment, $additionalDetails));

                    \DB::commit();

                    return response()->json(
                        [
                            'message' => 'Application for admission approved',
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
            $enrollment = Enrollment::where('id', '=', (int)$id)->with('student')
                ->first();

            $enrollment->update([
                'enrollment_status' => 'Declined',
                'remark' => $request->remarks

            ]);

            $notifiable = new User([
                'user_type' => 'student',
                'username' => $enrollment->student->LRN,
                'password' => \Hash::make(
                    $enrollment->student->lastname . $enrollment->student->LRN
                ),
                'email' => $enrollment->student_email
            ]);

            $additionalDetails = "Here is the details why your enrollment is declined. \n" . $request->remarks;

            Notification::send($notifiable, new StudentAdmissionNotification($enrollment, $additionalDetails));

            // Enrollment::where('student_id', '=',$id)->update(['enrollment_status' => "Declined" ]);
            \DB::commit();

            return response()->json(['success' => 'Application for admission declined']);
        } catch (\Exception $e) {
            \DB::rollback();

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
