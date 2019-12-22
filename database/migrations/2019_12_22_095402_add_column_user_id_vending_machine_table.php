<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnUserIdVendingMachineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_vending_machine', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index('uvm_user_id_ind')->nullable();
            $table->foreign('user_id', 'uvm_user_id_for')->references('id')->on('users')->onDelete('cascade');
            $table->integer('vending_machine_id')->unsigned()->index('uvm_vm_id_ind')->nullable();
            $table->foreign('vending_machine_id', 'uvm_vm_id_for')->references('id')->on('vending_machines')->onDelete('cascade');
            $table->integer('created_by')->unsigned()->index('uvm_created_by_ind')->nullable();
            $table->foreign('created_by', 'uvm_created_by_for')->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
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
        Schema::dropIfExists('user_vending_machine');
    }
}
