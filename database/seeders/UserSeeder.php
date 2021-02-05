<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Lcobucci\JWT\Claim\Factory;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::factory(UserFactory::class,1)->create();
    }
}
