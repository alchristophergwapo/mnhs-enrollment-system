<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\GradeLevel;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        User::create([
            'user_type' => 'admin',
            'username' => 'Administrator',
            'password' => \Hash::make('Password'),
            'remember_token' => \Str::random(10),
        ]);
        Teacher::factory()->count(50)->create();
        $students = Student::factory()->count(50)->create();
        foreach ($students as $student) {
            Enrollment::create([
                'grade_level' => rand(7, 10),
                'start_school_year' => 2020,
                'end_school_year' => 2021,
                'enrollment_status' => 'Pending',
                'student_id' => $student->id,
                'card_image' => '1618481441042_Use Case.png',
            ]);

            // User::create([
            //     'user_type' => 'student',
            //     'username' => $student->LRN,
            //     'password' => \Hash::make($student->lastname.$student->LRN)
            // ]);

        };

        $grade_levels = [
            [
                'grade_level' => 7
            ],
            [
                'grade_level' => 8
            ],
            [
                'grade_level' => 9
            ],
            [
                'grade_level' => 10
            ],
            [
                'grade_level' => 11
            ],
            [
                'grade_level' => 12
            ]
        ];
        foreach ($grade_levels as $grade_level) {
            GradeLevel::create($grade_level);
        }
    }
}
