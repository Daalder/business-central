<?php

namespace BusinessCentral\Jobs\Customer;


use BusinessCentral\API\HttpClient;
use BusinessCentral\Repositories\ReferenceRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Pionect\Backoffice\Models\Customer\Customer;

/**
 * Class CreateCustomer
 *
 * @package BusinessCentral\Jobs\Customer
 */
class CreateCustomer implements ShouldQueue
{
    use Dispatchable, SerializesModels, Queueable, InteractsWithQueue;

    /**
     * @var \Pionect\Backoffice\Models\Customer\Customer
     */
    protected $customer;

    /**
     * @var \BusinessCentral\Repositories\ReferenceRepository
     */
    protected $referenceRepository;

    /**
     * CreateCustomer constructor.
     *
     * @param  \Pionect\Backoffice\Models\Customer\Customer  $customer
     * @param  \BusinessCentral\Repositories\ReferenceRepository  $referenceRepository
     */
    public function __construct(Customer $customer, ReferenceRepository $referenceRepository)
    {
        $this->queue               = 'high';
        $this->customer            = $customer;
        $this->referenceRepository = $referenceRepository;
    }

    public function handle()
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
    public function tags()
    {
        return ['business-central', 'create-customer', 'customer', 'customer-'.$this->customer->id];
    }
}
