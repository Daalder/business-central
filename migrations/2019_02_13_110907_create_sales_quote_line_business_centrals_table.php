<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesQuoteLineBusinessCentralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_quote_line_business_central', function (Blueprint $table) {
            $table->integer('order_row_id')->unsigned();
            $table->string('business_central_id');
            $table->timestamps();

            $table->foreign('order_row_id', 'sql_order_row_id_business_central')
                ->references('id')
                ->on('orderrow')
                ->onDelete('cascade');

            $table->primary(['order_row_id', 'business_central_id'], 'sql_or_id_bc_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_quote_line_business_central');
    }
}
