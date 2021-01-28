<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductBusinessCentralTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_business_central', static function (Blueprint $table): void {
            $table->integer('product_id')->unsigned();
            $table->string('business_central_id');
            $table->timestamps();

            $table->foreign('product_id')
                ->references('id')
                ->on('product')
                ->onDelete('cascade');

            $table->primary(['product_id', 'business_central_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_business_central');
    }
}
