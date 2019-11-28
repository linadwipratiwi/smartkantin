<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMultipaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('multipayments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned()->index()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->integer('stand_id')->unsigned()->index()->nullable();
            $table->foreign('stand_id')->references('id')->on('vending_machines')->onDelete('cascade');
            $table->decimal('amount')->default(0);
            $table->string('payment_type')->nullable(); // in : out
            $table->string('notes')->nullable();

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
        Schema::dropIfExists('multipayments');
    }
}
