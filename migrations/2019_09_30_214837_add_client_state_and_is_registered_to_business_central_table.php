<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClientStateAndIsRegisteredToBusinessCentralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscription_business_central', function (Blueprint $table) {
            //
            $table->text('clientState')->nullable()->after('expirationDateTime');
            $table->boolean('isRegistered')->default(false)->after('clientState');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscription_business_central', function (Blueprint $table) {
            //
            $table->dropColumn(['clientState', 'isRegistered']);
        });
    }
}
