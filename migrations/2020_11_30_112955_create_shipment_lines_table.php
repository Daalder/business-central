<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_lines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('shipment_reference')->nullable();
            $table->integer('line')->nullable();
            $table->string('shipment_business_central_id')->nullable();
            $table->string('order_reference')->nullable();
            $table->string('order_business_central_id')->nullable();
            $table->string('sku')->nullable();
            $table->integer('amount')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipment_lines');
    }
}
