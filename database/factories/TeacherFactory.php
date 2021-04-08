<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Teacher::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'teacher_name' => $this->faker->name,
            'email' => preg_replace('/@example\..*/', '@gmail.com', $this->faker->safeEmail),
            'contact' => '09'.$this->faker->numberBetween(100000000,999999999)
        ];
    }
}
