<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductattributesetBusinessCentralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productattributeset_business_central', function (Blueprint $table) {
            $table->integer('productattributeset_id')->unsigned();
            $table->string('business_central_id');
            $table->timestamps();

            $table->foreign('productattributeset_id', 'pattributeset_id_business_central')
                ->references('id')
                ->on('productattributeset')
                ->onDelete('cascade');

            $table->primary(['productattributeset_id', 'business_central_id'], 'set_id_bc_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productattributeset_business_central');
    }
}
