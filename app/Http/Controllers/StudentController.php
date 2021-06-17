<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;

use App\Models\Section;
use App\Models\Student;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function getMyDetails($lrn)
    {
        $studentInfo = Student::with('enrollment')
            ->where('LRN', $lrn)
            ->first();
        $section = Section::with('adviser')
            ->where('id', $studentInfo->enrollment[count($studentInfo->enrollment) - 1]->student_section)
            ->first();

        $studentInfo['section'] = $section;
        return response()->json(
            [
                'myInfo' => $studentInfo,
                $studentInfo->enrollment[count($studentInfo->enrollment) - 1]
            ],
        );
    }


    public function getMyClassmates($section)
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
            if ($value->middlename == null) {
                $value->firstname .= " " . $value->lastname;
                $value->middlename = null;
            } else {
                $result = Str::substr($value->middlename, 0, 1);
                $value->firstname .= " " . $result . "." . " " . $value->lastname;
                $value->middlename = $result . ".";
            }
        }
        return response()->json(['classmates' => $classmates]);
    }


    public function getMySchedule($sectionId)
    {
        $schedules = \DB::table('schedules')
            ->where('schedules.section_id', '=', $sectionId)
            ->leftJoin('subjects', 'schedules.subject_id', 'subjects.id')
            ->leftJoin('teachers', 'schedules.teacher_id', 'teachers.id')
            ->select(
                'schedules.*',
                'subjects.subject_name',
                'teachers.teacher_name'
            )
            ->get();

        return response()->json(['sectionSchedules' => $schedules]);
    }
}
