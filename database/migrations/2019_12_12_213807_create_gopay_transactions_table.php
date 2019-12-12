<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGopayTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gopay_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('refer_type')->nullable(); // class topup, vmtransaction
            $table->integer('refer_type_id')->unsigned()->nullable();
            $table->integer('status')->default(0); // 0: new, 1. respon
            $table->dateTime('gopay_transaction_time')->nullable();
            $table->string('gopay_transaction_status')->nullable();
            $table->string('gopay_status_message')->nullable();
            $table->string('gopay_status_code')->nullable();
            $table->decimal('gopay_gross_amount')->nullable();
            $table->string('gopay_fraud_status')->nullable();
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
        Schema::dropIfExists('gopay_transactions');
    }
}
