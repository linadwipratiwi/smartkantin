<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendingMachineTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vending_machine_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vending_machine_id')->unsigned()->index()->nullable();
            $table->foreign('vending_machine_id')->references('id')->on('vending_machines')->onDelete('cascade');
            $table->integer('vending_machine_slot_id')->unsigned()->index()->nullable();
            $table->foreign('vending_machine_slot_id')->references('id')->on('vending_machine_slots')->onDelete('cascade');
            $table->integer('client_id')->unsigned()->index()->nullable();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->integer('customer_id')->unsigned()->index()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->decimal('hpp')->nullable(); // copy dari VM Slot
            $table->string('food_name')->nullable(); // copy dari VM Slot
            $table->decimal('selling_price_client')->nullable(); // harga jual dari client
            $table->decimal('profit_client')->nullable(); // profit dari setiap transaksi jika client menetapkan hpp
            $table->string('profit_platform_type')->nullable();  // percent or value
            $table->decimal('profit_platform_percent')->nullable();  // jika type persen, berapa persen ? misal 10
            $table->decimal('profit_platform_value')->nullable();  // jika type value, berapa value ? misal 10000
            $table->decimal('selling_price_vending_machine')->nullable(); // harga jual = harga jual dari client + profit dari setiap transaksi
            $table->integer('capacity')->nullable(); // jumlah maxsimal untuk setiap slot
            $table->integer('stock')->default(0); // stok sekarang
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
        Schema::dropIfExists('vending_machine_transactions');
    }
}
