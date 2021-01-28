<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductGroupBusinessCentralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_business_central', function (Blueprint $table) {
            $table->integer('group_id')->unsigned();
            $table->string('business_central_id');
            $table->timestamps();

            $table->foreign('group_id', 'group_id_business_central')
                ->references('id')
                ->on('groups')
                ->onDelete('cascade');

            $table->primary(['group_id', 'business_central_id'], 'g_id_bc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_business_central');
    }
}
