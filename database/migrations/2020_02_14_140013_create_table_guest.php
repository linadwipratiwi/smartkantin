<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableGuest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('guest', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_sekolah');
            $table->string('nama_kepala_sekolah');
            $table->string('alamat');
            $table->string('alamat_email');
            $table->string('jumlah_peserta_didik');
            $table->string('jumlah_pedagang_dikantin_sekolah');
            $table->string('nama_koperasi');
            $table->string('rencana_jadwal_paparan');
            $table->string('nama_pengisi');
            $table->string('no_ponsel_wa_pengisi');
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
        //
    }
}
