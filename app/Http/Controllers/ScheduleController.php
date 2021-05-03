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
            ->join('subjects', 'schedules.subject_id', 'subjects.id')
            ->join('sections', 'schedules.section_id', 'sections.id')
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

                $sexistingSched = Schedule::where('start_time', $sched['start_time'])
                    ->where('schedules.teacher_id', $sched['teacher_id'])
                    ->leftJoin('teachers', 'schedules.teacher_id', 'teachers.id')
                    ->leftJoin('subjects', 'schedules.subject_id', 'subjects.id')
                    ->select('schedules.day', 'teachers.teacher_name', 'subjects.subject_name')
                    ->first();
                if ($sched != null) {
                    $new = new Request([
                        'section_id' => $sched['section_id'],
                        'subject_id' => $sched['subject_id'],
                        'day' => $sched['day'],
                        'start_time' => $sched['start_time'],
                        'end_time' => $sched['end_time'],
                        'teacher_id' => $sched['teacher_id'],
                    ]);

                    if ($sexistingSched) {
                        \DB::rollBack();
                        return response(['has_sched' => $sexistingSched->teacher_name . ' already have schedule for ' . $sexistingSched->day . ' at ' . $sched['start_time'] . ' to ' . $sched['end_time'] . ' (Subject: ' . $sexistingSched->subject_name . ')', $sexistingSched], 400);
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
                $new = new Request([
                    'section_id' => $sched['section_id'],
                    'subject_id' => $sched['subject_id'],
                    'day' => $sched['day'],
                    'start_time' => $sched['start_time'],
                    'end_time' => $sched['end_time'],
                    'teacher_id' => $sched['teacher_id'],
                ]);
                $schedOnDb = Schedule::where('id', '=', $sched['id'])
                    ->where('day', '=', $sched['day'])
                    ->update([
                        'section_id' => $sched['section_id'],
                        'subject_id' => $sched['subject_id'],
                        'day' => $sched['day'],
                        'start_time' => $sched['start_time'],
                        'end_time' => $sched['end_time'],
                        'teacher_id' => $sched['teacher_id'],
                    ]);
                array_push($edited, $schedOnDb);
                // return response(['updatedSched' => $validatedSched]);

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
