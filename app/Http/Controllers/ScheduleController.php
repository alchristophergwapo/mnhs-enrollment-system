<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Schedule;
use Symfony\Component\Console\Input\Input;

class ScheduleController extends Controller
{
    //
    public function getSectionSchedule($sectionId)
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

    public function getTeacherSchedule($teacher_id)
    {
        $teacher_schedule = \DB::table('schedules')
            ->where('schedules.teacher_id', $teacher_id)
            ->leftJoin('subjects', 'schedules.subject_id', 'subjects.id')
            ->leftJoin('sections', 'schedules.section_id', 'sections.id')
            ->select('schedules.*', 'sections.name', 'subjects.subject_name')
            ->get();
        return response()->json(['schedules' => $teacher_schedule]);
    }

    public function addSchedules(Request $request)
    {
        $schedules = $request->all();
        try {
            \DB::beginTransaction();
            for ($i = 0; $i < count($schedules); $i++) {
                $sched = $schedules[$i];

                $existingSched = null;
                if ($sched['subject_id'] != 'null' && $sched['subject_id'] != null) {
                    $existingSched = Schedule::where('day', '=', $sched['day'])
                        ->where('schedules.teacher_id', $sched['teacher_id'])
                        ->where('start_time', $sched['start_time'])
                        ->join('teachers', 'schedules.teacher_id', 'teachers.id')
                        ->join('subjects', 'schedules.subject_id', 'subjects.id')
                        ->select('schedules.day', 'teachers.teacher_name', 'subjects.subject_name', 'schedules.start_time', 'schedules.end_time')
                        ->first();
                }
                $new = new Request([
                    'section_id' => $sched['section_id'],
                    'subject_id' => $sched['subject_id'],
                    'day' => $sched['day'],
                    'start_time' => $sched['start_time'],
                    'end_time' => $sched['end_time'],
                    'teacher_id' => $sched['teacher_id'],
                ]);

                if ($existingSched) {
                    \DB::rollBack();
                    return response(['has_sched' => $existingSched->teacher_name . ' already have schedule for ' . $existingSched->day . ' at ' . $existingSched->start_time . ' to ' . $existingSched->end_time . ' (Subject: ' . $existingSched->subject_name . ')', $existingSched], 400);
                }
                if ($sched['start_time'] && $sched['end_time']) {
                    $validatedSched = $new->validate([
                        'section_id' => 'required',
                        'day' => 'required|string|regex:/^[a-zA-Z]+$/u',
                    ]);

                    if ($validatedSched) {
                        Schedule::create($sched);
                    }
                } else {
                    Schedule::create($sched);
                }
            }
            \DB::commit();
            return response()->json([
                'success' => 'Schedule successfully added.',
            ]);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function editSchedules(Request $request)
    {
        $schedules = $request->all();
        // return response(['schedules' => $schedules]);
        try {
            \DB::beginTransaction();
            $edited = [];
            foreach ($schedules as $sched) {
                if ($sched != null) {
                    $schedOnDb = Schedule::where('id', '=', $sched['id'])
                        ->update([
                            'section_id' => $sched['section_id'],
                            'subject_id' => $sched['subject_id'],
                            'start_time' => $sched['start_time'],
                            'end_time' => $sched['end_time'],
                            'teacher_id' => $sched['teacher_id'],
                        ]);
                    array_push($edited, $schedOnDb);
                }
            }
            \DB::commit();

            return response()->json([
                'edited' => $edited,
                'success' => 'Successfully updated schedules.',

            ]);
        } catch (\Exception $e) {
            \DB::rollback();

            return response(['error' => $e->getMessage()], 500);
        }
    }
}
