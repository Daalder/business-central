<?php

declare(strict_types=1);

Route::get('not-in', 'BusinessCentralController@index');
Route::get('sidebar/{product}', 'BusinessCentralController@sidebar');
Route::get('sidebar/order/{id}', 'BusinessCentralController@orderSidebar');

Route::get('order/businesscentral/{order}', 'OrderController@businessCentral')->name('order.businesscentral');

// Route::any('subscription', 'SubscriptionController@index');
