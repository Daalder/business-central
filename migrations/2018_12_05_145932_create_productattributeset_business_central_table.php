<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductattributesetBusinessCentralTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('productattributeset_business_central', static function (Blueprint $table): void {
            $table->integer('productattributeset_id')->unsigned();
            $table->string('business_central_id');
            $table->timestamps();

            $table->foreign('productattributeset_id', 'pattributeset_id_business_central')
                ->references('id')
                ->on('product_attribute_sets')
                ->onDelete('cascade');

            $table->primary(['productattributeset_id', 'business_central_id'], 'set_id_bc_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productattributeset_business_central');
    }
}
