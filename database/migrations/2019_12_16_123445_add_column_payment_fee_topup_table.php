<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPaymentFeeTopupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_saldo', function ($table) {
            $table->string('topup_type')->nullable(); // gopay : manual
            $table->string('fee_topup_type')->nullable(); // jenis topup manual : persen apa value
            $table->decimal('fee_topup_percent')->nullable();  // jika type persen, berapa persen ? misal 10 (ambil dari table slot)
            $table->decimal('fee_topup_value')->nullable();
            $table->decimal('total_topup')->nullable();
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
            $table->dropColumn([
                'topup_type',
                'fee_topup_type',
                'fee_topup_percent',
                'fee_topup_value',
                'total_topup',
            ]);
        });
    }
}
