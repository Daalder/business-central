<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToShipments extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('shipments', static function (Blueprint $table): void {
            $table->string('load_status')->nullable()->after('order_id');
            $table->string('customer_name')->nullable()->after('order_id');
            $table->string('shipment_method_code')->nullable()->after('order_id');
            $table->string('salesperson_code')->nullable()->after('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', static function (Blueprint $table): void {
            
        });
    }
}
