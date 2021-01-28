<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionBusinessCentralTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscription_business_central', static function (Blueprint $table): void {
            $table->increments('id');
            $table->string('subscriptionId');
            $table->string('notificationUrl');
            $table->string('resourceUrl');
            $table->dateTime('expirationDateTime');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_business_central');
    }
}
