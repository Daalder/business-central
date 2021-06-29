<?php

namespace Daalder\BusinessCentral\Tests\API\Repositories;

use Daalder\BusinessCentral\API\HttpClient;
use Daalder\BusinessCentral\Repositories\CustomerRepository;
use Daalder\BusinessCentral\Models\CustomerBusinessCentral;
use Daalder\BusinessCentral\Models\ReferenceModel;
use Daalder\BusinessCentral\Repositories\ReferenceRepository;
use Daalder\BusinessCentral\Tests\TestCase as DaalderTestCase;
use Pionect\Daalder\Models\Customer\Customer;

class CustomerRepositoryTest extends DaalderTestCase
{


    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

    }

    /**
     * @test
     */
    public function testGet()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function testDelete()
    {
        $this->mock(HttpClient::class, function ($mock) {
            $mock->shouldReceive('delete')->once();
        });

        Customer::withoutSyncingToSearch(function () {
            /** @var Customer $customer */
            $customer = Customer::factory()->create();

            $reference = CustomerBusinessCentral::create([
                'customer_id'=> $customer->id,
                'business_central_id' => '12345'
            ]);

            $this->assertDatabaseHas('customers', ['id'=>$customer->id, 'firstname'=>$customer->firstname]);
            $this->assertDatabaseHas('customer_business_central', ['customer_id'=>$customer->id, 'business_central_id'=>'12345']);

            $customerRepository = app(CustomerRepository::class);

            $customerRepository->delete($customer);
        });
    }

    public function testCreate()
    {
        $this->markTestIncomplete();
    }

    public function testUpdate()
    {
        $this->markTestIncomplete();
    }
}
