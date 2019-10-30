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
            $table->dropColumn(['hpp', 'selling_price_client', 'profit_client', 'status']);
        });
    }
}
