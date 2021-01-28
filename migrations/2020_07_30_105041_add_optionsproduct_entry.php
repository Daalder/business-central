<?php

declare(strict_types=1);

use Daalder\BusinessCentral\Jobs\Product\PullProducts;
use Illuminate\Database\Migrations\Migration;
use Pionect\Backoffice\Models\Product\Product;

class AddOptionsproductEntry extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
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
     */
    public function down(): void
    {
        $optionProduct = Product::query()->where('sku', '0001')->first();
        if ($optionProduct) {
            $optionProduct->forceDelete();
        }
    }
}
