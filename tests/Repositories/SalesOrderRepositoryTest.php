<?php

namespace Daalder\BusinessCentral\Tests\Repositories;

use Daalder\BusinessCentral\API\HttpClient;
use Daalder\BusinessCentral\Models\OrderBusinessCentral;
use Daalder\BusinessCentral\Repositories\SalesOrderRepository;
use Daalder\BusinessCentral\Tests\TestCase as DaalderTestCase;
use Illuminate\Database\Eloquent\Model;
use Pionect\Daalder\Models\Order\Order;

/**
 * Class SalesOrderRepositoryTest
 * @package Daalder\BusinessCentral\Tests\Repositories
 * @covers SalesOrderRepository
 */
class SalesOrderRepositoryTest extends DaalderTestCase
{

    /**
     * @test
     * @covers SalesOrderRepository::create()
     */
    public function testCreate()
    {
        $this->mock(HttpClient::class, function ($mock) {
            $response = new \stdClass();
            $response->id = 1;

            $mock->shouldReceive('post')->andReturn($response);
        });

        Model::withoutEvents(function(){

            /** @var Order $order */
            $order = Order::factory()->create();
            $this->assertDatabaseHas('orders', ['id' => $order->id]);

            $itemRepository = app(SalesOrderRepository::class);
            $itemRepository->create($order);

            $this->assertDatabaseHas('order_business_central', ['order_id' => $order->id, 'business_central_id' => '1']);

        });
    }

    /**
     * @test
     * @covers SalesOrderRepository::update()
     */
    public function testUpdate()
    {
        $this->mock(HttpClient::class, function ($mock) {
            $response = new \stdClass();
            $response->id = 1;

            $mock->shouldReceive('patch')->andReturn($response);
        });

        Model::withoutEvents(function(){

            /** @var Order $order */
            $order = Order::factory()->create();
            OrderBusinessCentral::create([
                'order_id'=> $order->id,
                'business_central_id' => '12345'
            ]);

            $this->assertDatabaseHas('orders', ['id' => $order->id]);
            $this->assertDatabaseHas('order_business_central', ['order_id' => $order->id, 'business_central_id' => '12345']);

            $itemRepository = app(SalesOrderRepository::class);
            $itemRepository->update($order);

        });
    }

    /**
     * @test
     * @covers SalesOrderRepository::delete()
     */
    public function testDelete()
    {
        $this->mock(HttpClient::class, function ($mock) {
            $response = new \stdClass();
            $response->id = 1;

            $mock->shouldReceive('delete')->andReturn($response);
        });

        Model::withoutEvents(function(){

            /** @var Order $order */
            $order = Order::factory()->create();

            OrderBusinessCentral::create([
                'order_id'=> $order->id,
                'business_central_id' => '12345'
            ]);

            $this->assertDatabaseHas(Order::table(), ['id' => $order->id]);

            $itemRepository = app(SalesOrderRepository::class);
            $itemRepository->delete($order);

            //$this->assertDatabaseMissing('order_business_central', ['order_id' => $order->id, 'business_central_id' => '12345']);
        });
    }
}
