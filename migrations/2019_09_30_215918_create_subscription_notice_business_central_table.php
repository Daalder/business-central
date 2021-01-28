<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionNoticeBusinessCentralTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscription_notice_business_central', static function (Blueprint $table): void {
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
     */
    public function down(): void
    {
        Schema::table('subscription_notice_business_central', static function (Blueprint $table): void {
            Schema::dropIfExists('subscription_notice_business_central');
        });
    }
}
