<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPaymentFeeClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function ($table) {
            $table->string('fee_topup_manual_type')->nullable(); // jenis topup manual : persen apa value
            $table->decimal('fee_topup_manual_percent')->nullable();  // jika type persen, berapa persen ? misal 10 (ambil dari table slot)
            $table->decimal('fee_topup_manual_value')->nullable();

            $table->string('fee_topup_gopay_type')->nullable(); // jenis topup manual : persen apa value
            $table->decimal('fee_topup_gopay_percent')->nullable();  // jika type persen, berapa persen ? misal 10 (ambil dari table slot)
            $table->decimal('fee_topup_gopay_value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function ($table) {
            $table->dropColumn([
                'fee_topup_manual_type',
                'fee_topup_manual_percent',
                'fee_topup_manual_value',
                'fee_topup_gopay_type',
                'fee_topup_gopay_percent',
                'fee_topup_gopay_value'
            ]);
        });
    }
}
