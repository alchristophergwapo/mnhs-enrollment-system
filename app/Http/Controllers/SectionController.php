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
class SectionController extends Controller
{
    public function allSections()
    {
        $sections = Section::with('gradelevel')
            // ->join('teachers', 'sections.teacher_id', 'teachers.id')
            // ->select('sections.*', 'teachers.teacher_name')
            ->with('adviser')
            ->get();
        return response()->json(['sections' => $sections], 200);
    }

    //Function For Adding Section In Junior High School
    public function addAnySection(SectionRequest $request)
    {
        $addSection = $request->validated();
        // return response($request->schedules);
        if ($addSection) {
            try {
                \DB::beginTransaction();

                $schedules = $request->schedules;
                $newScheds = [];
                $section = Section::create($addSection);

                $grade = GradeLevel::where(
                    'grade_level',
                    $request['grade']
                )->first();

                $updated = Section::where('id', '=', $section->id)->update([
                    'gradelevel_id' => $grade->id,
                ]);

                for ($i = 0; $i < count($schedules); $i++) {
                    $sched = $schedules[$i];

                    $new = new Request([
                        'section_id' => $section->id,
                        'subject_id' => $sched['subject_id'],
                        'day' => $sched['day'],
                        'start_time' => $sched['start_time'],
                        'end_time' => $sched['end_time'],
                        'teacher_id' => $sched['teacher_id'],
                    ]);
                    $validatedSched = $new->validate([
                        'section_id' => 'required',
                        'subject_id' => 'required',
                        'day' => 'required|string|regex:/^[a-zA-Z]+$/u',
                        'start_time' => 'required|date_format:h:i',
                        'end_time' => 'required|date_format:h:i',
                        'teacher_id' => 'required',
                    ]);

                    if ($validatedSched) {
                        $newSched = Schedule::create($validatedSched);
                        array_push($newScheds, $validatedSched);
                    }
                }

                if ($request->teacher == null) {
                    \DB::commit();
                    return ['message' => 'Successfully Added!'];
                } else {
                    $teachers = Teacher::where('id', '=', $request->teacher)
                        ->with('section')
                        ->first();
                    if ($teachers->section_id != null) {
                        return response()->json(
                            [
                                'failed' => $teachers->section->name,
                                'teacher' => $teachers->teacher_name,
                            ],
                            200
                        );
                    } else {
                        $updateSection = Section::where(
                            'id',
                            '=',
                            $section->id
                        )->update([
                            'teacher_id' => $teachers->id,
                            'gradelevel_id' => $grade->id,
                        ]);
                        $updateTeacher = Teacher::where(
                            'id',
                            '=',
                            $teachers->id
                        )->update(['section_id' => $section->id]);
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

            return response()->json(
                [
                    'message' => 'Successfully Added!',
                    'sections' => $arraySection,
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    //Function For Deleting Any Sections
    public function delAnySection($id)
    {
        try {
            \DB::beginTransaction();
            $section = Section::where('id', '=', $id)
                ->with('gradelevel')
                ->get();
            $objectGrade = Str::of(
                $section->get(0)->gradelevel->sections
            )->split('/[\s,]+/');
            $remove = $objectGrade->diff([$section->get(0)->id]);
            $newSection = null;
            foreach ($remove as $val) {
                if ($newSection == null) {
                    $newSection .= $val;
                } else {
                    $newSection .= ',' . $val;
                }
            }
            //delete automatically if teacher_id is not null
            if ($section->get(0)->teacher_id == null) {
                $gradelevel_section = Gradelevel::findOrFail(
                    $section->get(0)->gradelevel_id
                );
                $gradelevel_section->update(['sections' => $newSection]);
                $del = Section::findOrFail($id)->delete();
                \DB::commit();
                return [
                    'message' => 'Successfully Added!',
                    'section' =>
                        'Grade ' . $section->get(0)->gradelevel->grade_level,
                ];
            } else {
                $gradelevel_section = Gradelevel::findOrFail(
                    $section->get(0)->gradelevel_id
                );
                $gradelevel_section->update([
                    'sections' => $newSection,
                ]);
                $teacher = Teacher::where(
                    'id',
                    '=',
                    $section->get(0)->teacher_id
                )->first();
                $teacher->section_id = null;
                $teacher->save();
                $del = Section::findOrFail($id)->delete();
                \DB::commit();
                return [
                    'message' => 'Successfully Added!',
                    'section' =>
                        'Grade ' . $section->get(0)->gradelevel->grade_level,
                ];
            }
        } catch (\Exception $e) {
            \DB::rollback();
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
                if ($id != 'update') {
                    if ($request->teacher == null) {
                        $section = Section::where('id', '=', $id)->update([
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
                            $request->teacher
                        )->first();
                        if ($infoTeacher->section_id != null) {
                            if ($infoTeacher->section_id == $id) {
                                $section = Section::where(
                                    'id',
                                    '=',
                                    $id
                                )->update([
                                    'name' => $request['name'],
                                    'capacity' => $request['capacity'],
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
                                            ->name,
                                    ],
                                    200
                                );
                            }
                        } else {
                            $currentSec_idTeacher = Teacher::where(
                                'section_id',
                                '=',
                                $id
                            )->update(['section_id' => null]);
                            $updateSec = Section::where('id', '=', $id)->update(
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
                    $Teachers = Teacher::where('id', '=', $request->teacher)
                        ->with('section')
                        ->get();

                    //currentSection_id from the section name of the $request['teacher']
                    $currentSection_id = Teacher::where(
                        'section_id',
                        '=',
                        $request->updateId
                    )->update(['section_id' => null]);
                    //currentTeacher_id from the current assigned Teacher to be updated to null
                    $currentTeacher = Section::where(
                        'id',
                        '=',
                        $Teachers->get(0)->section->id
                    )->update(['teacher_id' => null]);
                    //The new data values from the currentSection of the "Id" you want to be update
                    $updateSec = Section::where(
                        'id',
                        '=',
                        $request->updateId
                    )->update([
                        'name' => $request['name'],
                        'capacity' => $request['capacity'],
                        'teacher_id' => $Teachers->get(0)->id,
                    ]);
                    //Set section_id to null from the assigned id of the request teacher
                    $updateTeacher = Teacher::where(
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
