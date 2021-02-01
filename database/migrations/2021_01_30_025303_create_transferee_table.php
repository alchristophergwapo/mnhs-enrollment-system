<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfereeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transferee', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->string('last_grade_completed');
            $table->date('last_year_completed');
            $table->string('last_school_attended');
            $table->string('last_school_ID');
            $table->string('last_school_address');
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
        Schema::dropIfExists('transferee');
    }
}
