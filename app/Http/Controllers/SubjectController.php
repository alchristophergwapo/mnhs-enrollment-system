<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Http\Requests\SubjectRequest;

use App\Models\Subject;
use App\Models\GradeLevel;
use App\Models\Teacher;

class SubjectController extends Controller
{
    public function allSubjectsByGrLevel($grade)
    {
        $gradelevel = GradeLevel::where('grade_level', '=', $grade)->first();
        $subjects = Subject::where('grade_level_id', '=', $gradelevel->id)
            ->join('teachers', 'subjects.teacher_id', 'teachers.id')
            ->select('subjects.*', 'teachers.teacher_name')
            ->get();

        return response(['subjects' => $subjects]);
    }

    public function addSubjectInGrLevel(Request $request)
    {
        $datas = $request->all();
        $added = 0;
        // return response(count($data));
        foreach ($datas as $data) {
            $gradelevel = GradeLevel::where(
                'grade_level',
                '=',
                $data['grade_level_id']
            )->first();
            // return response($gradelevel);
            // $teacher = Teacher::where(
            //     'teacher_name',
            //     $data['teacher_id']
            // )->first();
            $subOnDb = Subject::where('subject_name', '=', $data['name'])
                ->where('grade_level_id', '=', $gradelevel->id)
                ->first();

            if ($subOnDb == null) {
                $newRequest = [
                    'subject_name' => $data['name'],
                    'teacher_id' => $data['teacher_id'],
                    'grade_level_id' => $gradelevel->id,
                ];

                $validated = Validator::make($newRequest, [
                    'subject_name' => [
                        'required',
                        'string',
                        'min:2',
                        'max:255',
                    ],
                    'teacher_id' => ['required'],
                    'grade_level_id' => ['required', 'integer'],
                ])->validate();

                if ($validated) {
                    try {
                        \DB::beginTransaction();
                        $subject = Subject::create($validated);
                        \DB::commit();
                        $added += 1;
                    } catch (\Exception $e) {
                        \DB::rollback();
                        return response(['error' => $e->getMessage()], 500);
                    }
                }
            } else {
                return response(
                    [
                        'error' =>
                            $data['name'] .
                            ' already exist on Grade ' .
                            $gradelevel->grade_level,
                    ],
                    400
                );
            }
        }

        if ($added == count($datas)) {
            return response(['success' => 'Added Successfully.']);
        }
    }
}
