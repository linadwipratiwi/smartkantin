<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnStatusPaymentTransferSaldoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_saldo', function ($table) {
            $table->integer('payment_status')->unsigned()->default(1); // 1. lunas 2. pending
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfer_saldo', function ($table) {
            $table->dropColumn(['payment_status']);
        });
    }
}
