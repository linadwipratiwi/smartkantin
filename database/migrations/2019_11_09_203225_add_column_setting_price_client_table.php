<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSettingPriceClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function ($table) {
            $table->string('profit_platform_type')->nullable();  // percent or value (ambil dari table slot)
            $table->decimal('profit_platform_percent')->nullable();  // jika type persen, berapa persen ? misal 10 (ambil dari table slot)
            $table->decimal('profit_platform_value')->nullable();  // jika type value, berapa value ? misal 10000 (ambil dari table slot)
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
            $table->dropColumn(['profit_platform_type', 'profit_platform_percent', 'profit_platform_value']);
        });
    }
}
