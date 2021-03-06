<?php

namespace Database\Factories;

use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

class SectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Section::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Section '.$this->faker->numberBetween(1,5),
            'capacity' => 50,
            'total_students' => 0,
            'teacher_id' => 1,
            'students_id' => NULL,
            'gradelevel_id' => 1
        ];
    }
}
