<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendingMachineSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vending_machine_slots', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('alias');
            $table->integer('vending_machine_id')->unsigned()->index()->nullable();
            $table->foreign('vending_machine_id')->references('id')->on('vending_machines')->onDelete('cascade');
            $table->string('food_name')->nullable();
            $table->decimal('hpp')->nullable(); // harga pokok penjualan
            $table->decimal('selling_price_client')->nullable(); // harga jual dari client
            $table->decimal('profit_client')->nullable(); // profit dari setiap transaksi jika client menetapkan hpp
            $table->decimal('profit_platform')->nullable();  // profit dari setiap transaksi (by percent / per transaksi)
            $table->decimal('selling_price_vending_machine')->nullable(); // harga jual = harga jual dari client + profit dari setiap transaksi
            $table->integer('capacity')->nullable(); // jumlah maxsimal untuk setiap slot
            $table->integer('stock')->default(0); // stok sekarang
            $table->timestamp('expired_date')->nullable(); // pengaturan satu hari ambil dari tabel setting
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
        Schema::dropIfExists('vending_machine_slots');
    }
}
