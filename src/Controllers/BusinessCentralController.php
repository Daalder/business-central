<?php

namespace BusinessCentral\Controllers;

use App\Models\Orders\Order;
use App\Models\Products\Product;
use BusinessCentral\Repositories\ProductRepository;
use Pionect\Backoffice\Http\Controllers\BaseController;
use Pionect\Backoffice\Models\Product\Type;
use Pionect\Backoffice\Models\ProductAttribute\Repositories\GroupRepository;
use Pionect\Backoffice\Models\ProductAttribute\Repositories\SetRepository;

/**
 * Class BusinessCentralController
 *
 * @package BusinessCentral\Controllers
 */
class BusinessCentralController extends BaseController
{
    /**
     * @param  \Pionect\Backoffice\Models\ProductAttribute\Repositories\GroupRepository  $groupRepository
     * @param  \BusinessCentral\Repositories\ProductRepository  $productRepository
     * @param  \Pionect\Backoffice\Models\ProductAttribute\Repositories\SetRepository  $setRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(GroupRepository $groupRepository, ProductRepository $productRepository, SetRepository $setRepository)
    {
        $products = $productRepository->getNotSyncedProductsOverview($groupRepository);

        $data['types']    = Type::types();
        $data['products'] = $products;

        $data['productAttributeSets'] = $setRepository->allOrderBy('name', 'asc');

        return view("backoffice::pages.product.list", $data);
    }

    /**
     * @param  \App\Models\Products\Product  $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sidebar(Product $product)
    {
        return view("business-central::sidebar.product", ['product' => $product]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function orderSidebar($id)
    {
        return view("business-central::sidebar.order", ['order' => Order::findOrFail($id)]);
    }

}
