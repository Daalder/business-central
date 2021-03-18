<?php

namespace Daalder\BusinessCentral\Tests\API\Repositories;

use Daalder\BusinessCentral\API\HttpClient;
use Daalder\BusinessCentral\API\Repositories\CustomerRepository;
use Daalder\BusinessCentral\Models\CustomerBusinessCentral;
use Daalder\BusinessCentral\Models\ReferenceModel;
use Daalder\BusinessCentral\Repositories\ReferenceRepository;
use Daalder\BusinessCentral\Tests\TestCase as DaalderTestCase;
use Pionect\Backoffice\Models\Customer\Customer;

class CustomerRepositoryTest extends DaalderTestCase
{
    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

    }

    public function testGet()
    {
        
    }

    /** @test */
    public function testDelete()
    {
        $this->mock(HttpClient::class, function ($mock) {
            $mock->shouldReceive('delete')->once();
        });

        Customer::withoutSyncingToSearch(function () {
            /** @var Customer $customer */
            $customer = factory(Customer::class)->create();

            $reference = CustomerBusinessCentral::create([
                'customer_id'=> $customer->id,
                'business_central_id' => '12345'
            ]);

            $this->assertDatabaseHas('customer', ['id'=>$customer->id, 'firstname'=>$customer->firstname]);
            $this->assertDatabaseHas('customer_business_central', ['customer_id'=>$customer->id, 'business_central_id'=>'12345']);

            $customerRepository = app(CustomerRepository::class);

            $customerRepository->delete($customer);
        });
    }

    public function testCreate()
    {

    }

    public function testUpdate()
    {

    }
}
