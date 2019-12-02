<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendingMachineTransactionDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vending_machine_transaction_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('transaction_id')->unsigned()->index('vmtd_transac_ind')->nullable();
            $table->foreign('transaction_id', 'vmtd_transac_forg')->references('id')->on('vending_machine_transactions')->onDelete('cascade');
            $table->integer('vending_machine_slot_id')->unsigned()->index()->nullable();
            $table->foreign('vending_machine_slot_id')->references('id')->on('vending_machine_slots')->onDelete('cascade');
            $table->integer('food_id')->unsigned()->index()->nullable();
            $table->foreign('food_id')->references('id')->on('foods')->onDelete('cascade');
            $table->integer('quantity')->unsigned()->nullable();
            $table->decimal('hpp')->nullable(); // copy dari VM Slot (ambil dari table slot)
            $table->string('food_name')->nullable(); // copy dari VM Slot (ambil dari table slot)
            $table->decimal('selling_price_client')->nullable(); // harga jual dari client (ambil dari table slot)
            $table->decimal('profit_client')->nullable(); // profit dari setiap transaksi jika client menetapkan hpp (ambil dari table slot)
            $table->string('profit_platform_type')->nullable();  // percent or value (ambil dari table slot)
            $table->decimal('profit_platform_percent')->nullable();  // jika type persen, berapa persen ? misal 10 (ambil dari table slot)
            $table->decimal('profit_platform_value')->nullable();  // jika type value, berapa value ? misal 10000 (ambil dari table slot)
            $table->decimal('profit_platform')->nullable();  // hasil sesungguhnya profit platform. (hitung manual ketika transaksi)
            $table->decimal('selling_price_vending_machine')->nullable(); // (ambil dari table slot)
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
        Schema::dropIfExists('vending_machine_transaction_detail');
    }
}
