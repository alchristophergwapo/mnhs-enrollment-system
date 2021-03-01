<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
           // $table->id();
            $table->increments('id');
            $table->string('name');
            $table->integer('capacity');
            $table->integer('total_students')->default(0);
            $table->integer('teacher_id')->nullable();
           // $table->integer('student_id')->nullable();
            $table->json('students_id')->nullable();
            $table->integer('gradelevel_id')->nullable();
            $table->foreign('gradelevel_id')->references('id')->on('grade_levels');
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
        Schema::dropIfExists('sections');
    }

}
