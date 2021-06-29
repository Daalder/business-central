<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Jobs\Customer;

use Daalder\BusinessCentral\API\HttpClient;
use Daalder\BusinessCentral\Repositories\ReferenceRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Pionect\Daalder\Models\Customer\Customer;

/**
 * Class CreateCustomer
 *
 * @package BusinessCentral\Jobs\Customer
 */
class CreateCustomer implements ShouldQueue
{
    use Dispatchable, SerializesModels, Queueable, InteractsWithQueue;

    protected \Pionect\Daalder\Models\Customer\Customer $customer;

    protected \BusinessCentral\Repositories\ReferenceRepository $referenceRepository;

    /**
     * CreateCustomer constructor.
     *
     * @param  \BusinessCentral\Repositories\ReferenceRepository  $referenceRepository
     */
    public function __construct(Customer $customer, ReferenceRepository $referenceRepository)
    {
        $this->queue = 'high';
        $this->customer = $customer;
        $this->referenceRepository = $referenceRepository;
    }

    public function handle(): void
    {
        /**
         * @var HttpClient $client
         */
        $client = App::make(HttpClient::class);

        $client->customer()->create($this->customer);
    }

    /**
     * @return array
     */
    public function tags(): array
    {
        return ['business-central', 'create-customer', 'customer', 'customer-'.$this->customer->id];
    }
}
