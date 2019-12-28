<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDatePreorderVendingMachineTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vending_machine_transactions', function ($table) {
            $table->dateTime('preorder_date')->useCurrent(); // tanggal preorder. default hari ini
            $table->boolean('is_preorder')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vending_machine_transactions', function ($table) {
            $table->dropColumn([
                'preorder_date', 'is_preorder'
            ]);
        });
    }
}
