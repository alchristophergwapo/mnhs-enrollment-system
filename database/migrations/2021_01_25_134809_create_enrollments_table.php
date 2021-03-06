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
            $table->string('enrollment_remarks')->default('NO REMARKS');
            $table->string('card_image')->nullable();
            $table->string('remark')->nullable();
            $table->string('specialization')->nullable();
            $table->integer('student_section')->nullable();
            $table->string('student_email')->nullable();
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
