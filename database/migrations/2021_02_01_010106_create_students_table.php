<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->string('PSA')->nullable();
            $table->string('LRN');
            $table->integer('average');
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->date('birthdate');
            $table->integer('age');
            $table->string('gender');
            $table->string('IP');
            $table->string('IP_community')->nullable();
            $table->string('mother_tongue');
            $table->string('contact')->nullable();
            $table->string('address');
            $table->string('zipcode');
            $table->string('father')->nullable();
            $table->string('mother')->nullable();
            $table->string('guardian');
            $table->string('parent_number');
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
        Schema::dropIfExists('students');
    }
}
