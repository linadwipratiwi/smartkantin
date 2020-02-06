<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableWithdraw extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('withdraw', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vending_machine_id')->unsigned()->index()->nullable();
            $table->foreign('vending_machine_id')->references('id')->on('vending_machines')->onDelete('cascade');
            $table->decimal('jumlah', 12, 2)->nullable();
            $table->string('no_rekening')->nullable();
            $table->string('bank')->nullable();
            $table->integer('status')->unsigned()->nullable();
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
        //
    }
}
