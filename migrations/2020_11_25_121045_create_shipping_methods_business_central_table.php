<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShippingMethodsBusinessCentralTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipping_methods_business_central', static function (Blueprint $table): void {
            $table->integer('shipping_method_id')->unsigned();
            $table->string('business_central_id');
            $table->timestamps();

            $table->foreign('shipping_method_id', 'shipping_method_id_business_central')
                ->references('id')
                ->on('shipping_methods')
                ->onDelete('cascade');

            $table->primary(['shipping_method_id', 'business_central_id'], 'shm_id_bc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('shipping_methods_business_central');
    }
}
