<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference')->nullable();
            $table->string('business_central_id')->nullable();
            $table->string('track_and_trace')->nullable();
            $table->unsignedBigInteger('provider_id')->nullable();
            $table->integer('shipping_address_id')->unsigned()->nullable();
            $table->integer('order_id')->unsigned()->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('work_description')->nullable();
            $table->integer('number_of_colli')->nullable();
            $table->string('trip_number')->nullable();
            $table->integer('week_number')->nullable();
            $table->boolean('shipped')->nullable();
            $table->boolean('delivered')->nullable();
            $table->date('estimated_delivery_date')->nullable();
            $table->timestamps();
        });

        Schema::table('shipments', function (Blueprint $table) {
            $table->foreign('provider_id')->references('id')->on('shipping_providers');
            $table->foreign('shipping_address_id')->references('id')->on('address');
            $table->foreign('order_id')->references('id')->on('order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipments');
    }
}
