<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrollmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id');
            $table->integer('grade_level');
            $table->year('start_school_year');
            $table->year('end_school_year');
            $table->string('enrollment_status');
            $table->string('card_image');
<<<<<<< HEAD
            $table->string('remark')->nullable();
            $table->string('student_section');
=======
            $table->string('student_section')->nullable();
>>>>>>> 2848ad1f625438867003f309e7df4a0a44b53743
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enrollments');
    }
}
