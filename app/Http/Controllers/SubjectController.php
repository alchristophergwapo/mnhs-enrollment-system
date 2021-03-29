<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\SubjectRequest;

use App\Models\Subject;
use App\Models\GradeLevel;
use App\Models\Teacher;

class SubjectController extends Controller
{
    public function allSubjectsByGrLevel($grade)
    {
        $gradelevel = GradeLevel::where('grade_level', '=', $grade)->first();
        $subjects = Subject::where(
            'grade_level_id',
            '=',
            $gradelevel->id
        )->get();

        return response(['subjects' => $subjects]);
    }

    public function addSubjectInGrLevel(Request $request)
    {
        $datas = $request->all();
        // return response(count($data));
        foreach ($datas as $data) {
            $gradelevel = GradeLevel::where(
                'grade_level',
                '=',
                $data['grade_level']
            )->first();
            $teacher = Teacher::where('name', $data['teacher_id'])->first();
            $newRequest = new Request($data);
            $newRequest->teacher_id = $teacher->id;
            $newRequest->grade_level_id = $gradelevel->id;
            $validated = $newRequest->validate([
                'name' => ['required', 'string', 'min:2', 'max:255'],
                'teacher_id' => ['required'],
                'grade_level_id' => ['required', 'integer'],
            ]);
            return response($validated);
            if ($validated) {
                try {
                    \DB::beginTransaction();
                    $subject = Subject::create([
                        'name' => $validated->name,
                        'teacher_id' => $validated->teacher_id,
                        'grade_level_id' => $gradelevel->id,
                    ]);
                    // \DB::commit();
                    return response(['success' => 'Added Successfully.']);
                } catch (\Exception $e) {
                    \DB::rollback();
                    return response(['error' => $e->getMessage()], 500);
                }
            }
        }
    }
}
