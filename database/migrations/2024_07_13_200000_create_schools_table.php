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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique;
            $table->string('code')->unique;
            $table->foreignId('group_id')->nullable();
            $table->tinyInteger('group_admin')->nullable();
            $table->tinyInteger('class_num')->nullable();//1為準備好，不再上傳
            $table->tinyInteger('ready')->nullable();//1為準備好，不再上傳
            $table->tinyInteger('situation')->nullable();//1為編好班了
            $table->unsignedInteger('township_id')->nullable();            
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
        Schema::dropIfExists('schools');
    }
};
