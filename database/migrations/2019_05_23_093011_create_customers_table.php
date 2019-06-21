<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('identity_type')->nullable();
            $table->string('identity_number')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->integer('register_at_client_id')->unsigned()->index()->nullable();
            $table->foreign('register_at_client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->integer('register_at_vending_machine_id')->unsigned()->index()->nullable();
            $table->foreign('register_at_vending_machine_id')->references('id')->on('vending_machines')->onDelete('cascade');
            $table->decimal('saldo')->nullable()->default(0); // saldo. Khusus YDFS: saldo 0

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
        Schema::dropIfExists('customers');
    }
}
