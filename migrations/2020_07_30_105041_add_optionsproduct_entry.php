<?php

use Illuminate\Database\Migrations\Migration;
use Pionect\Backoffice\Models\Product\Product;
use BusinessCentral\Jobs\Product\PullProducts;

class AddOptionsproductEntry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $optionProduct = Product::query()->where('sku', '001')->first()->replicate();
        $optionProduct->sku = '0001';
        $optionProduct->price = 0;
        $optionProduct->name = $optionProduct->description = 'Product Option';

        $optionProduct->save();
        $optionProduct->searchable();
        PullProducts::dispatch($optionProduct->sku);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $optionProduct = Product::query()->where('sku', '0001')->first();
        if($optionProduct) {
            $optionProduct->forceDelete();
        }
    }
}
