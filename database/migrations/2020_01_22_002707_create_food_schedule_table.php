<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoodScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_schedule', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('food_id')->unsigned()->index()->nullable();
            $table->foreign('food_id')->references('id')->on('foods')->onDelete('cascade');
            $table->integer('senin')->unsigned()->nullable();
            $table->integer('selasa')->unsigned()->nullable();
            $table->integer('rabu')->unsigned()->nullable();
            $table->integer('kamis')->unsigned()->nullable();
            $table->integer('jumat')->unsigned()->nullable();
            $table->integer('sabtu')->unsigned()->nullable();
            $table->integer('minggu')->unsigned()->nullable();
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
        Schema::dropIfExists('food_schedule');
    }
}
