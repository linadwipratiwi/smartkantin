<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnFoodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('foods', function ($table) {
            $table->decimal('hpp')->nullable(); // harga pokok penjualan
            $table->decimal('selling_price_client')->nullable(); // harga jual dari client
            $table->decimal('profit_client')->nullable(); // profit dari setiap transaksi jika client menetapkan hpp
            $table->string('profit_platform_type')->nullable();  // percent or value
            $table->decimal('profit_platform_percent')->nullable();  // jika type persen, berapa persen ? misal 10
            $table->decimal('profit_platform_value')->nullable();  // jika type value, berapa value ? misal 10000
            $table->decimal('selling_price_vending_machine')->nullable(); // harga jual = harga jual dari client + profit dari setiap transaksi
            $table->boolean('status')->default(true); //1 active 0 non active
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
            $table->dropColumn(['hpp', 'selling_price_client', 'profit_client', 'profit_platform_type', 'profit_platform_percent', 'profit_platform_value', 'selling_price_vending_machine']);
        });
    }
}
