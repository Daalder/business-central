<?php

namespace Daalder\BusinessCentral\Tests\Repositories;

use Daalder\BusinessCentral\API\HttpClient;
use Daalder\BusinessCentral\Models\OrderRowBusinessCentral;
use Daalder\BusinessCentral\Repositories\SalesOrderLineRepository;
use Daalder\BusinessCentral\Tests\TestCase as DaalderTestCase;
use Illuminate\Database\Eloquent\Model;
use Pionect\Daalder\Models\Order\Orderrow;
use Pionect\Daalder\Models\VatRate\VatRate;

/**
 * Class SalesOrderLineRepositoryTest
 * @package Daalder\BusinessCentral\Tests\Repositories
 * @covers SalesOrderLineRepository
 */
class SalesOrderLineRepositoryTest extends DaalderTestCase
{
    /**
     * @test
     * @covers SalesOrderLineRepository::create()
     */
    public function testCreate()
    {
        $this->markTestSkipped();

        $this->mock(HttpClient::class, function ($mock) {
            $response = new \stdClass();
            $response->id = 1;

            $mock->shouldReceive('post')->andReturn($response);
        });

        Model::withoutEvents(function(){

            /** @var OrderRow $orderRow */
            $orderRow = OrderRow::factory()->create(['vat_rate'=>21,'vat_type'=>'type']);

            $this->assertDatabaseHas('order_rows', ['id' => $orderRow->id]);

            $itemRepository = app(SalesOrderLineRepository::class);
            $itemRepository->create($orderRow);

            $this->assertDatabaseHas('order_row_business_central', ['order_row_id' => $orderRow->id, 'business_central_id' => '1']);

        });
    }

    /**
     * @test
     * @covers SalesOrderLineRepository::update()
     */
    public function testUpdate()
    {
        $this->markTestSkipped();

        $this->mock(HttpClient::class, function ($mock) {
            $response = new \stdClass();
            $response->id = 1;

            $mock->shouldReceive('patch')->andReturn($response);
        });

        Model::withoutEvents(function(){

            /** @var OrderRow $orderRow */
            $orderRow = OrderRow::factory()->create();
            OrderRowBusinessCentral::create([
                'order_row_id'=> $orderRow->id,
                'business_central_id' => '12345'
            ]);

            $this->assertDatabaseHas('order_rows', ['id' => $orderRow->id]);
            $this->assertDatabaseHas('order_row_business_central', ['order_row_id' => $orderRow->id, 'business_central_id' => '1']);

            $itemRepository = app(SalesOrderLineRepository::class);
            $itemRepository->update($orderRow);

        });
    }

    /**
     * @test
     * @covers SalesOrderLineRepository::delete()
     */
    public function testDelete()
    {
        $this->markTestSkipped();

        $this->mock(HttpClient::class, function ($mock) {
            $response = new \stdClass();
            $response->id = 1;

            $mock->shouldReceive('delete')->andReturn($response);
        });

        Model::withoutEvents(function(){

            /** @var OrderRow $orderRow */
            $orderRow = OrderRow::factory()->create();
            OrderRowBusinessCentral::create([
                'order_row_id'=> $orderRow->id,
                'business_central_id' => '12345'
            ]);

            $this->assertDatabaseHas('order_rows', ['id' => $orderRow->id]);

            $itemRepository = app(SalesOrderLineRepository::class);
            $itemRepository->delete($orderRow);

        });
    }
}
