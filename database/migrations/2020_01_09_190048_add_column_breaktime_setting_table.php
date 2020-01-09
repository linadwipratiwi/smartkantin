<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnBreaktimeSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vending_machine_transactions', function ($table) {
            $table->integer('break_time_setting_id')->unsigned()->index()->nullable();
            $table->foreign('break_time_setting_id')->references('id')->on('break_time_settings')->onDelete('cascade');
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
                'break_time_setting_id'
            ]);
        });
    }
}
