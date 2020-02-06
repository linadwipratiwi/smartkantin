<?php

use App\Models\VendingMachine;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendingMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vending_machines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('alias'); // untuk api : vm_client_xx_xx
            $table->string('production_year')->nullable();
            $table->integer('client_id')->unsigned()->index()->nullable();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->integer('version_firmware_id')->unsigned()->index()->nullable();
            $table->foreign('version_firmware_id')->references('id')->on('firmwares')->onDelete('cascade');
            $table->integer('version_ui_id')->unsigned()->index()->nullable();
            $table->foreign('version_ui_id')->references('id')->on('firmwares')->onDelete('cascade');
            $table->string('location')->nullable();
            $table->string('ip')->nullable();
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
        Schema::dropIfExists('vending_machines');
    }
}
