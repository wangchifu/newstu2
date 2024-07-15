<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('semester_year');
            $table->string('no');
            $table->string('class')->nullable();
            $table->string('num')->nullable();
            $table->tinyInteger('sex');
            $table->string('name');
            $table->string('id_number');
            $table->string('old_school')->nullable();
            $table->tinyInteger('type')->nullable();//0一般生 1特殊生  2雙胞胎同班  3雙胞胎不同班 4三胞胎全同班 5三胞胎全不同班
            $table->tinyInteger('special')->nullable();//1特殊生  null無   
            $table->tinyInteger('subtract')->nullable();//減1~3人
            $table->string('another_no')->nullable();//相關流水號
            $table->string('ps')->nullable();
            $table->string('with_teacher')->nullable();
            $table->string('without_teacher')->nullable();
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
};
