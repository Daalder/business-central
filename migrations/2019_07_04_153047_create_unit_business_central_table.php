<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnitBusinessCentralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unit_business_central', function (Blueprint $table) {
            $table->integer('unit_id')->unsigned();
            $table->string('business_central_id');
            $table->timestamps();

            $table->foreign('unit_id')
                ->references('id')
                ->on('unit')
                ->onDelete('cascade');

            $table->primary(['unit_id', 'business_central_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unit_business_central');
    }
}
