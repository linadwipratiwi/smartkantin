<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDanaTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('dana_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('refer_type')->nullable(); // class topup, vmtransaction
            $table->integer('refer_type_id')->unsigned()->nullable();
            $table->integer('status')->default(0); // 0: new, 1. respon
            $table->dateTime('dana_transaction_time')->nullable();
            $table->string('dana_transaction_status')->nullable();
            $table->string('dana_status_message')->nullable();
            $table->string('dana_status_code')->nullable();
            $table->decimal('dana_gross_amount')->nullable();
            $table->string('dana_fraud_status')->nullable();
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
