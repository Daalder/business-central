<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderRowBusinessCentralTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_row_business_central', static function (Blueprint $table): void {
            $table->integer('order_row_id')->unsigned();
            $table->string('business_central_id');
            $table->timestamps();

            $table->foreign('order_row_id', 'order_row_id_business_central')
                ->references('id')
                ->on('orderrow')
                ->onDelete('cascade');

            $table->primary(['order_row_id', 'business_central_id'], 'or_id_bc_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_row_business_central');
    }
}
