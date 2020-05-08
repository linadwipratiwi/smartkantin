<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBahanProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bahan_produk', function (Blueprint $table) {
            $table->integer('bahans_id')->unsigned()->index();
            $table->foreign('bahans_id')->references('id')->on('bahans')->onDelete('cascade');

            $table->integer('produks_id')->unsigned()->index();
            $table->foreign('produks_id')->references('id')->on('produks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bahan_produk');
    }
}
