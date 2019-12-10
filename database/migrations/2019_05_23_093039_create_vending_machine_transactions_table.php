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
            $table->decimal('hpp')->nullable(); // copy dari VM Slot (ambil dari table slot)
            $table->string('food_name')->nullable(); // copy dari VM Slot (ambil dari table slot)
            $table->decimal('selling_price_client')->nullable(); // harga jual dari client (ambil dari table slot)
            $table->decimal('profit_client')->nullable(); // profit dari setiap transaksi jika client menetapkan hpp (ambil dari table slot)
            $table->string('profit_platform_type')->nullable();  // percent or value (ambil dari table slot)
            $table->decimal('profit_platform_percent')->nullable();  // jika type persen, berapa persen ? misal 10 (ambil dari table slot)
            $table->decimal('profit_platform_value')->nullable();  // jika type value, berapa value ? misal 10000 (ambil dari table slot)
            $table->decimal('profit_platform')->nullable();  // hasil sesungguhnya profit platform. (hitung manual ketika transaksi)
            $table->decimal('selling_price_vending_machine')->nullable(); // (ambil dari table slot)
            $table->integer('quantity')->default(1); // jumlah item yg diambil, default
            $table->integer('status_transaction')->nullable(); // 1: success, 2: gagal, 
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
