<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Http\Requests\SubjectRequest;

use App\Models\Subject;
use App\Models\GradeLevel;
use App\Models\Teacher;

use function React\Promise\Stream\first;

class SubjectController extends Controller
{
    public function allSubjectsByGrLevel($grade)
    {
        try {
            $gradelevel = GradeLevel::where('grade_level', '=', $grade)->first();
            $subjects = \DB::table('subjects')->where('subjects.grade_level_id', '=', $gradelevel->id)
                ->join('teachers', 'subjects.teacher_id', 'teachers.id')
                ->select('subjects.*', 'teachers.teacher_name')
                ->get();

            return response(['subject' => $subjects]);
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()()]);
        }
    }

    public function addSubjectInGrLevel(SubjectRequest $request)
    {
        $data = $request->all();
        $gradelevel = GradeLevel::where(
            'grade_level',
            '=',
            $data['grade_level_id']
        )->first();
        $subOnDb = Subject::where('subject_name', '=', $data['subject_name'])
            ->where('grade_level_id', '=', $gradelevel->id)
            ->first();

        if ($subOnDb == null) {
            $validated = $request->validated();

            if ($validated) {
                try {
                    \DB::beginTransaction();
                    Subject::create([
                        'subject_name' => $request->subject_name,
                        'teacher_id' => $request->teacher_id,
                        'grade_level_id' => $gradelevel->id,
                    ]);
                    \DB::commit();
                } catch (\Exception $e) {
                    \DB::rollback();
                    return response(['error' => $e->getMessage()], 500);
                }
            }
        }
    }

    public function updateSubject(SubjectRequest $request)
    {
        $subject = Subject::where('id', '=', $request->id)
            ->first();
        try {
            \DB::beginTransaction();
            $teacher = \DB::table('teachers')->where('teachers.id', '=', $request->teacher_id)
                ->join('subjects', 'teachers.id', 'subjects.teacher_id')
                ->select('teachers.*', 'subjects.subject_name')
                ->first();
            if (!$teacher) {
                $validated = $request->validated();

                if ($validated) {
                    $subject->update($validated);
                }
                \DB::commit();
                return response(['success' => 'Successfully updated.']);
            } else {
                return response(['failed' => $teacher->teacher_name . ' is already assigned on ' . $teacher->subject_name], 422);
            }
        } catch (\Exception $e) {
            \DB::rollBack();
            return response(['error' => $e->getMessage()]);
        }
    }

    public function deleteSubject($id)
    {
        Subject::where('id', $id)->delete();
        return response(['success' => 'Successfully deleted!']);
    }
}
