<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderBusinessCentralTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_business_central', static function (Blueprint $table): void {
            $table->integer('order_id')->unsigned();
            $table->string('business_central_id');
            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')
                ->on('order')
                ->onDelete('cascade');

            $table->primary(['order_id', 'business_central_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_business_central');
    }
}
