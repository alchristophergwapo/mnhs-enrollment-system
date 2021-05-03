<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Models\GradeLevel;
use App\Models\Section;
use App\Models\Teacher;
use App\Models\Schedule;

use Illuminate\Support\Str;
use App\Http\Requests\SectionRequest;
use App\Http\Requests\UpdateSectionRequest;
use App\Models\Enrollment;

class SectionController extends Controller
{
    public function allSections()
    {
        $sections = Section::with('gradelevel')
            ->with('adviser')
            ->get();
        return response()->json(['sections' => $sections], 200);
    }

    public function allSectionsWithNoAdviser()
    {
        $sections = Section::where('teacher_id', '=', null)->get();

        return response(['sections' => $sections]);
    }

    //Function For Adding Section In Junior High School
    public function addAnySection(SectionRequest $request)
    {
        $addSection = $request->validated();

        if ($addSection) {
            try {
                \DB::beginTransaction();
                $section = Section::create($addSection);
                if ($request->teacher == null) {
                    $grade = GradeLevel::where(
                        'grade_level',
                        '=',
                        $request['grade']
                    )->first();
                    Section::where('id', '=', $section->id)->update([
                        'gradelevel_id' => $grade->id,
                    ]);
                    \DB::commit();
                    return [
                        'message' => 'Successfully Added!',
                        'section' => $section,
                    ];
                } else {
                    $teachers = Teacher::where(
                        'id',
                        '=',
                        $request->teacher_id
                    )->first();
                    if ($teachers->section_id != null) {
                        $teachers = Teacher::where('id', '=', $teachers->id)
                            ->with('section')
                            ->get();

                        return response()->json(
                            [
                                'failed' => $teachers->get(0)->section->name,
                                'teacher' => $teachers->get(0)->teacher_name,
                            ],
                            200
                        );
                    } else {

                        Section::where(
                            'id',
                            '=',
                            $section->id
                        )->update(['teacher_id' => $teachers->id]);

                        Teacher::where(
                            'id',
                            '=',
                            $teachers->id
                        )->update(['section_id' => $section->id]);

                        $updateGrade = GradeLevel::where(
                            'grade_level',
                            '=',
                            $request['grade']
                        )->first();

                        $section->update([
                            'gradelevel_id' => $updateGrade->id,
                        ]);

                        \DB::commit();
                        return ['message' => 'Successfully Added!'];
                    }
                }
            } catch (\Exception $e) {
                \DB::rollback();
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
    }

    //Function For Getting The All Sections In GradelevelSections in Allsections.Vue
    public function allGradeLevelSections()
    {
        try {
            $arraySection = [];
            $data = Section::cursor();
            foreach ($data as $val) {
                if ($val->gradelevel) {
                    $val->gradelevel->makeHidden([
                        'students',
                        'sections',
                        'created_at',
                        'updated_at',
                    ]);
                    if ($val->teacher_id == null) {
                        $val->gradelevel_id = $val->teacher_id;
                        $val->teacher_id = 'No Adviser';
                        array_push(
                            $arraySection,
                            $val->makeHidden([
                                'student_id',
                                'created_at',
                                'updated_at',
                            ])
                        );
                    } else {
                        $teacher = Teacher::where(
                            'id',
                            '=',
                            $val->teacher_id
                        )->first();
                        $val->gradelevel_id = $val->teacher_id;
                        $val->teacher_id = $teacher->name;
                        array_push(
                            $arraySection,
                            $val->makeHidden([
                                'student_id',
                                'created_at',
                                'updated_at',
                            ])
                        );
                    }
                }
            }

            return response()->json([
                'message' => 'Successfully Added!',
                'sections' => $arraySection,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    //Function For Updating A Specific Section
    public function updateSection(UpdateSectionRequest $request, $id)
    {
        $updateSection = $request->validated();
        if ($updateSection) {
            try {
                \DB::beginTransaction();
                $section = Section::where('id', '=', $id)->first();
                if ($id != 'update') {
                    if ($request->teacher_id == null) {
                        $section->update([
                            'name' => $request['name'],
                            'capacity' => $request['capacity'],
                        ]);
                        \DB::commit();
                        return [
                            'message' => 'Successfully Updated!',
                            'section' => $section,
                        ];
                    } else {
                        $infoTeacher = Teacher::where(
                            'id',
                            '=',
                            $request->teacher_id
                        )->first();
                        if ($infoTeacher->section_id != null) {
                            if ($infoTeacher->section_id == $id) {
                                $section->update([
                                    'name' => $request['name'],
                                    'capacity' => $request['capacity'],
                                    'teacher_id' => $request->teacher_id
                                ]);
                                \DB::commit();
                                return [
                                    'message' => 'Successfully updated!',
                                    'section' => $section,
                                ];
                            } else {
                                $assignTeacher = Teacher::where(
                                    'id',
                                    '=',
                                    $request->teacher
                                )
                                    ->with('section')
                                    ->get();
                                return response()->json(
                                    [
                                        'failed' => $assignTeacher->get(0)
                                            ->section->name,
                                        'teacher' => $assignTeacher->get(0)
                                            ->teacher_name,
                                    ],
                                    200
                                );
                            }
                        } else {
                            Teacher::where(
                                'section_id',
                                '=',
                                $id
                            )->update(['section_id' => null]);
                            Section::where('id', '=', $id)->update(
                                [
                                    'name' => $request['name'],
                                    'capacity' => $request['capacity'],
                                    'teacher_id' => $infoTeacher->id,
                                ]
                            );
                            $infoTeacher->update(['section_id' => $id]);
                            \DB::commit();
                            return ['message' => 'Successfully updated!'];
                        }
                    }
                } else {
                    $Teachers = Teacher::where('id', '=', $request->teacher_id)
                        ->with('section')
                        ->get();

                    //currentSection_id from the section name of the $request['teacher']
                    Teacher::where(
                        'section_id',
                        '=',
                        $request->updateId
                    )->update(['section_id' => null]);
                    //currentTeacher_id from the current assigned Teacher to be updated to null
                    Section::where(
                        'id',
                        '=',
                        $Teachers->get(0)->section->id
                    )->update(['teacher_id' => null]);
                    //The new data values from the currentSection of the "Id" you want to be update
                    Section::where(
                        'id',
                        '=',
                        $request->updateId
                    )->update([
                        'name' => $request['name'],
                        'capacity' => $request['capacity'],
                        'teacher_id' => $Teachers->get(0)->id,
                    ]);
                    //Set section_id to null from the assigned id of the request teacher
                    Teacher::where(
                        'id',
                        '=',
                        $Teachers->get(0)->id
                    )->update(['section_id' => $request->updateId]);
                    \DB::commit();
                    return response()->json(
                        ['newTeacher' => 'Successfully Updated!'],
                        200
                    );
                }
            } catch (\Exception $e) {
                \DB::rollback();
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
    }
}
