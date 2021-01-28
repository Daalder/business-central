<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionNoticeBusinessCentralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_notice_business_central', function (Blueprint $table) {
            //
            $table->increments('id');
            $table->integer('subscription_id')->unsigned();
            $table->string('resourceUrl');
            $table->string('changeType');
            $table->dateTime('lastModifiedDateTime');
            $table->timestamps();

            $table->foreign('subscription_id', 'subscription_id_business_central')
                ->references('id')
                ->on('subscription_business_central')
                ->onDelete('cascade');

            // Not sure about use cases
            $table->index(['subscription_id', 'changeType'], 's_id_ct');
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
            Schema::dropIfExists('subscription_notice_business_central');
        });
    }
}
