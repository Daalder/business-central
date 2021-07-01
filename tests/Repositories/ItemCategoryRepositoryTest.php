<?php

namespace Daalder\BusinessCentral\Tests\Repositories;

use Daalder\BusinessCentral\API\HttpClient;
use Daalder\BusinessCentral\Commands\PullFromBusinessCentral;
use Daalder\BusinessCentral\Models\SetBusinessCentral;
use Daalder\BusinessCentral\Repositories\ItemCategoryRepository;
use Daalder\BusinessCentral\Tests\TestCase as DaalderTestCase;
use Illuminate\Database\Eloquent\Model;
use Pionect\Daalder\Models\ProductAttribute\Set;

/**
 * Class ItemRepositoryTest
 * @package Daalder\BusinessCentral\Tests\API\Repositories
 * @covers ItemCategoryRepository
 */
class ItemCategoryRepositoryTest extends DaalderTestCase
{
    /**
     * @test
     * @covers ItemCategoryRepository::create()
     */
    public function testCreate()
    {
        $this->mock(HttpClient::class, function ($mock) {
            $response = new \stdClass();
            $response->id = 1;

            $mock->shouldReceive('post')->andReturn($response);
        });


        Model::withoutEvents(function() {

            $set = Set::withoutSyncingToSearch(function() {
                return Set::factory()->create();
            });

            $this->assertDatabaseHas('product_attribute_sets', ['id' => $set->id]);

            $itemCategoryRepository = app(ItemCategoryRepository::class);
            $itemCategoryRepository->create($set);

            $this->assertDatabaseHas('productattributeset_business_central', ['productattributeset_id' => $set->id, 'business_central_id' => '1']);

        });
    }

    /**
     * @test
     * @covers ItemCategoryRepository::update()
     */
    public function testUpdate()
    {
        $this->mock(HttpClient::class, function ($mock) {
            $response = new \stdClass();
            $response->id = 1;

            $mock->shouldReceive('patch')->andReturn($response);
        });


        Model::withoutEvents(function() {

            $set = Set::withoutSyncingToSearch(function() {
                return Set::factory()->create();
            });

            $reference = SetBusinessCentral::create([
                'productattributeset_id'=> $set->id,
                'business_central_id' => '12345'
            ]);

            $this->assertDatabaseHas('product_attribute_sets', ['id' => $set->id]);
            $this->assertDatabaseHas('productattributeset_business_central', ['productattributeset_id' => $set->id, 'business_central_id' => '12345']);

            $itemCategoryRepository = app(ItemCategoryRepository::class);
            $itemCategoryRepository->update([], $reference->business_central_id);

        });
    }

    /**
     * @test
     * @covers ItemCategoryRepository::delete()
     */
    public function testDelete()
    {
        $this->mock(HttpClient::class, function ($mock) {
            $response = new \stdClass();
            $response->id = 1;

            $mock->shouldReceive('delete')->andReturn($response);
        });


        Model::withoutEvents(function() {

            $set = Set::withoutSyncingToSearch(function() {
                return Set::factory()->create();
            });

            SetBusinessCentral::create([
                'productattributeset_id'=> $set->id,
                'business_central_id' => '12345'
            ]);

            $this->assertDatabaseHas('product_attribute_sets', ['id' => $set->id]);
            $this->assertDatabaseHas('productattributeset_business_central', ['productattributeset_id' => $set->id, 'business_central_id' => '12345']);

            $itemCategoryRepository = app(ItemCategoryRepository::class);
            $itemCategoryRepository->delete($set);

        });
    }

    /**
     * @test
     * @covers ItemCategoryRepository::pullReferences()
     */
    public function testPullReferences()
    {

        $set = Set::withoutSyncingToSearch(function() {
            return Set::factory()->create();
        });

        $this->mock(HttpClient::class, function ($mock) use ($set) {
            $response = new \stdClass();
            $response->value = [];

            $sub = new \stdClass();
            $sub->id = 1;
            $sub->code = $set->id;

            $response->value[] = $sub;

            $mock->shouldReceive('get')->andReturn($response);
        });

        $itemCategoryRepository = app(ItemCategoryRepository::class);
        $itemCategoryRepository->pullReferences(new PullFromBusinessCentral());

    }
}
