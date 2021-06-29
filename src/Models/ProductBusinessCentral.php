<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Models;

use Pionect\Daalder\Models\Product\Product;

/**
 * Class ProductBusinessCentral
 *
 * @package BusinessCentral\Models
 *
 * @property string business_central_id
 * @property int product_id
 * @property Product product
 */
class ProductBusinessCentral extends ReferenceModel
{
    protected $key = 'product_id';
    protected $table = 'product_business_central';
    protected $fillable = ['product_id', 'business_central_id'];
    protected $dates = ['created_at', 'updated_at'];

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
