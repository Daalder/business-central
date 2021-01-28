<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerBusinessCentralTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customer_business_central', static function (Blueprint $table): void {
            $table->integer('customer_id')->unsigned();
            $table->string('business_central_id');
            $table->timestamps();

            $table->foreign('customer_id', 'customer_id_business_central')
                ->references('id')
                ->on('customer')
                ->onDelete('cascade');

            $table->primary(['customer_id', 'business_central_id'], 'c_id_bc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_business_central');
    }
}
