<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransferSaldosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // (YDFS ke Masjid)  atau (Masjid ke Mustahiq)
        Schema::create('transfer_saldo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('from_type'); // class client
            $table->string('from_type_id')->nullable(); // id client
            $table->string('to_type')->nullable(); // class customer
            $table->string('to_type_id')->nullable(); // id customer
            $table->decimal('saldo', 16, 2)->nullable();
            $table->integer('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('transfer_saldo');
    }
}
