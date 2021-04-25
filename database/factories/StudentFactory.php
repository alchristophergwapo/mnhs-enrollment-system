<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $gender = $this->faker->randomElement(['Male', 'Female']);

        return [
            'LRN' => '303000'.$this->faker->numberBetween(100000,999999),
            'average' => $this->faker->numberBetween(75,100),
            'firstname' => $this->faker->firstName($gender),
            'middlename' =>$this->faker->lastName,
            'lastname' => $this->faker->lastName,
            'birthdate' => $this->faker->date,
            'age' => $this->faker->numberBetween(11,30),
            'gender' => $gender,
            'IP' => 'No',
            'IP_community' => '',
            'mother_tongue' => 'Bisaya',
            'contact' => '09'.$this->faker->numberBetween(100000000,999999999),
            'address' => $this->faker->address,
            'zipcode' => 6000,
            'father' => $this->faker->name('Male'),
            'mother' => $this->faker->name('Female'),
            'guardian' => $this->faker->name,
            'parent_number' => '09'.$this->faker->numberBetween(100000000,999999999),
        ];
    }
}
