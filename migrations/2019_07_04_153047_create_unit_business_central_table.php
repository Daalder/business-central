<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitBusinessCentralTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('unit_business_central', static function (Blueprint $table): void {
            $table->integer('unit_id')->unsigned();
            $table->string('business_central_id');
            $table->timestamps();

            $table->foreign('unit_id')
                ->references('id')
                ->on('units')
                ->onDelete('cascade');

            $table->primary(['unit_id', 'business_central_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_business_central');
    }
}
