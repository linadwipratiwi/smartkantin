<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnProfitVmFoodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('foods', function ($table) {
            $table->string('profit_platform_type')->nullable();  // percent or value
            $table->decimal('profit_platform_percent')->nullable();  // jika type persen, berapa persen ? misal 10
            $table->decimal('profit_platform_value')->nullable();  // jika type value, berapa value ? misal 10000
            $table->decimal('selling_price_vending_machine')->nullable(); // harga jual = harga jual dari client + profit dari setiap transaksi
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('foods', function ($table) {
            $table->dropColumn(['profit_platform_type', 'profit_platform_percent', 'profit_platform_value', 'selling_price_vending_machine']);
        });
    }
}
