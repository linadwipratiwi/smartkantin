<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockMutationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_mutations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vending_machine_id')->unsigned()->index()->nullable();
            $table->foreign('vending_machine_id')->references('id')->on('vending_machines')->onDelete('cascade');
            $table->integer('vending_machine_slot_id')->unsigned()->index()->nullable();
            $table->foreign('vending_machine_slot_id')->references('id')->on('vending_machine_slots')->onDelete('cascade');
            $table->integer('client_id')->unsigned()->index()->nullable();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->string('food_name')->nullable(); // copy dari VM Slot
            $table->decimal('hpp')->nullable(); // copy dari VM Slot
            $table->integer('stock')->default(0); // stok yang ditambahkan / dikurangi
            $table->decimal('selling_price_client')->nullable(); // copy dari VM Slot
            $table->string('type')->nullable(); // stock_opname / transaction
            $table->integer('created_by')->unsigned()->index()->nullable(); // jika dari transaction, default dari system
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('stock_mutations');
    }
}
