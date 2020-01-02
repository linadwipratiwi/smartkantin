<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnResponGopayTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gopay_transactions', function ($table) {
            $table->text('url_deeplink')->nullable();
            $table->text('url_qrcode')->nullable();
            $table->text('url_get_status')->nullable();
            $table->text('url_cancel')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gopay_transactions', function ($table) {
            $table->dropColumn([
                'url_deeplink', 'url_qrcode', 'url_get_status', 'url_cancel'
            ]);
        });
    }
}
