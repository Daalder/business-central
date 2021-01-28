<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Validators;

use Exception;
use Pionect\Backoffice\Http\Requests\Product\CreateProductRequest;


class ProductBusinessCentralValidator extends BusinessCentralValidator
{
    /**
     * @return array
     *
     * @throws Exception
     */
    public function rules(): array
    {
        return (new CreateProductRequest())->rules();
    }
}
