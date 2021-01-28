<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentLinesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipment_lines', static function (Blueprint $table): void {
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
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_lines');
    }
}
