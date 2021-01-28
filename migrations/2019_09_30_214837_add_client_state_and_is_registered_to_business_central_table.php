<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClientStateAndIsRegisteredToBusinessCentralTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subscription_business_central', static function (Blueprint $table): void {
            $table->text('clientState')->nullable()->after('expirationDateTime');
            $table->boolean('isRegistered')->default(false)->after('clientState');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_business_central', static function (Blueprint $table): void {
            $table->dropColumn(['clientState', 'isRegistered']);
        });
    }
}
