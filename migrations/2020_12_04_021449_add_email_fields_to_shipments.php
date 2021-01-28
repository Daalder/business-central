<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailFieldsToShipments extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('shipments', static function (Blueprint $table): void {
            $table->date('planned_delivery_date')->nullable()->after('order_id');
            $table->boolean('sent_as_email_cust')->nullable()->after('order_id');
            $table->date('shipment_date')->nullable()->after('order_id');
            $table->dateTime('pakbon_printed_at')->nullable()->after('order_id');
            $table->dateTime('picking_list_printed_at')->nullable()->after('order_id');
            $table->dateTime('last_email_sent_time_cust')->nullable()->after('order_id');
            $table->dateTime('last_email_sent_time_complete')->nullable()->after('order_id');
            $table->dateTime('last_email_sent_time')->nullable()->after('order_id');
            $table->boolean('sent_as_email')->nullable()->after('order_id');
            $table->boolean('sent_as_email_complete')->nullable()->after('order_id');
            $table->string('external_document_no')->nullable()->after('order_id');
            $table->integer('sort_order')->nullable()->after('order_id');
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
