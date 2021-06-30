<?php

namespace Daalder\BusinessCentral\Tests\Repositories;

use Daalder\BusinessCentral\API\HttpClient;
use Daalder\BusinessCentral\Models\GroupBusinessCentral;
use Daalder\BusinessCentral\Models\ProductBusinessCentral;
use Daalder\BusinessCentral\Repositories\DimensionRepository;
use Daalder\BusinessCentral\Repositories\ItemRepository;
use Daalder\BusinessCentral\Tests\TestCase as DaalderTestCase;
use Illuminate\Database\Eloquent\Model;
use Pionect\Daalder\Models\Product\Group;
use Pionect\Daalder\Models\Product\Product;
use Mockery;

/**
 * Class ItemRepositoryTest
 * @package Daalder\BusinessCentral\Tests\API\Repositories
 * @covers ItemRepository
 */
class ItemRepositoryTest extends DaalderTestCase
{
    /**
     * @test
     * @covers ItemRepository::create()
     */
    public function testCreate()
    {
        $this->mock(HttpClient::class, function ($mock) {
            $response = new \stdClass();
            $response->id = 1;

            $mock->shouldReceive('post')->andReturn($response);
        });

//        $this->mock(DimensionRepository::class, function ($mock) {
//            $response = new \stdClass();
//            $response->id = 1;
//
//            /** @var Mockery\Mock $mock */
//            $mock->shouldReceive('create')->once();
//        });

        Model::withoutEvents(function(){
            /** @var Group $product */
            $group = Group::factory()->create();

            $reference = GroupBusinessCentral::create([
                'group_id'=> $group->id,
                'business_central_id' => '12345'
            ]);

            /** @var Product $product */
            $product = Product::factory()->create(['group_id' => $group->id]);

            $this->assertDatabaseHas('product', ['id' => $product->id, 'sku' => $product->sku]);

            $itemRepository = app(ItemRepository::class);
            $itemRepository->create($product);

            $this->assertDatabaseHas('product_business_central', ['product_id' => $product->id, 'business_central_id' => '1']);

        });
    }

    /**
     * @test
     * @covers ItemRepository::update()
     */
    public function testUpdate()
    {
        $this->mock(HttpClient::class, function ($mock) {
            $response = new \stdClass();
            $response->id = 1;

            $mock->shouldReceive('patch')->andReturn($response);
        });

        Model::withoutEvents(function(){
            /** @var Product $product */
            $product = Product::factory()->create();

            $reference = ProductBusinessCentral::create([
                'product_id'=> $product->id,
                'business_central_id' => '12345'
            ]);

            $this->assertDatabaseHas('product', ['id' => $product->id, 'sku' => $product->sku]);

            $itemRepository = app(ItemRepository::class);
            $itemRepository->update($product);
        });
    }
}
