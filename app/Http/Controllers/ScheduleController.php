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
}
