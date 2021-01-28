<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Validators;

use Exception;
use Pionect\Backoffice\Http\Api\Requests\Product\StoreProductRequest;

class ProductBusinessCentralValidator extends BusinessCentralValidator
{
    /**
     * @return array
     *
     * @throws Exception
     */
    public function rules(): array
    {
        return (new StoreProductRequest())->rules();
    }
}
