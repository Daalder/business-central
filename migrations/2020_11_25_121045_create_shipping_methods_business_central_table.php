<?php

use Illuminate\Database\Migrations\Migration;
use Pionect\Backoffice\Models\Product\Product;
use BusinessCentral\Jobs\Product\PullProducts;
use Illuminate\Database\Schema\Blueprint;

class CreateShippingMethodsBusinessCentralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_methods_business_central', function (Blueprint $table) {
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
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('shipping_methods_business_central');
    }
}
