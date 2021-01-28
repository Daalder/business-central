<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerBusinessCentralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_business_central', function (Blueprint $table) {
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
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_business_central');
    }
}
