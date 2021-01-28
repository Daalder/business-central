<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailFieldsToShipments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipments', function (Blueprint $table) {
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
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipments', function (Blueprint $table) {
            //
        });
    }
}
