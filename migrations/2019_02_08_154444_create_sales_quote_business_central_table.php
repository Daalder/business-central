<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesQuoteBusinessCentralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_quote_business_central', function (Blueprint $table) {
            $table->integer('order_id')->unsigned();
            $table->string('business_central_id');
            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')
                ->on('order')
                ->onDelete('cascade');

            $table->primary(['order_id', 'business_central_id'], 'sqbc_order_id_business_central_id_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_quote_business_central');
    }
}
