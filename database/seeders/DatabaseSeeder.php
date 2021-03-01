<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \DB::table('grade_levels')->insert([
        //     'grade_level' =>12,
           
        // ]);
        
        \DB::table('users')->insert([
            'username' => 'admin',
            'password' => \Hash::make('Administrator'),
            'user_type' => 'admin',
            'remember_token' => \Str::random(10),
        ]);
    }
}
