<?php

namespace BusinessCentral\Models;

use Pionect\Backoffice\Models\Product\Product;

/**
 * Class ProductBusinessCentral
 *
 * @package BusinessCentral\Models
 * @property string business_central_id
 * @property int product_id
 * @property Product product
 */
class ProductBusinessCentral extends ReferenceModel
{
    protected $table = 'product_business_central';
    protected $fillable = ['product_id', 'business_central_id'];
    protected $dates = ['created_at', 'updated_at'];
    public $key = 'product_id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
