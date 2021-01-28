<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsProcessedToSubscriptionNoticeBusinessCentralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscription_notice_business_central', function (Blueprint $table) {
            //
            $table->boolean('isProcessed')->default(false)->after('lastModifiedDateTime');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscription_notice_business_central', function (Blueprint $table) {
            //
            $table->dropColumn('isProcessed');
        });
    }
}