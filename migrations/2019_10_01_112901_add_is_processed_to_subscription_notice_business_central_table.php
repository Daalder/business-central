<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsProcessedToSubscriptionNoticeBusinessCentralTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subscription_notice_business_central', static function (Blueprint $table): void {
            $table->boolean('isProcessed')->default(false)->after('lastModifiedDateTime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_notice_business_central', static function (Blueprint $table): void {
            $table->dropColumn('isProcessed');
        });
    }
}
