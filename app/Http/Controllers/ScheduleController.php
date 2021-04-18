<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Schedule;

class ScheduleController extends Controller
{
    //
    public function getSectionSchedule($sectionId)
    {
        $schedules = \DB::table('schedules')
            ->where('schedules.section_id', '=', $sectionId)
            ->join('subjects', 'schedules.subject_id', 'subjects.id')
            ->join('teachers', 'schedules.teacher_id', 'teachers.id')
            ->select(
                'schedules.*',
                'subjects.subject_name',
                'teachers.teacher_name'
            )
            ->get();

        // if (count($schedules) > 0) {

        // }

        return response(['schedules' => $schedules]);
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

                $new = new Request([
                    'section_id' => $sched['section_id'],
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
                    Schedule::create($validatedSched);
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
            foreach ($schedules as $sched) {
                $new = new Request([
                    'section_id' => $sched['section_id'],
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
                    // return response(['updatedSched' => $validatedSched]);
                }
            }
            \DB::commit();

            return response()->json([
                'success' => 'Successfully updated schedules.',
            ]);
        } catch (\Exception $e) {
            \DB::rollback();

            return response(['error' => $e->getMessage()]);
        }
    }
}
