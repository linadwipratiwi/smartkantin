<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTypeFoodInSlotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vending_machine_slots', function ($table) {
            $table->string('photo')->nullable();
            $table->integer('category_id')->unsigned()->index()->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->integer('ref_stock_mutation_id')->unsigned()->index()->nullable(); // digunakan untuk mencari stok opname yang barusan di edit
            $table->foreign('ref_stock_mutation_id')->references('id')->on('stock_mutations')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vending_machine_slots', function ($table) {
            $table->dropForeign(['category_id', 'ref_stock_mutation_id']);
            $table->dropColumn(['category_id', 'photo', 'ref_stock_mutation_id']);
        });
    }
}
