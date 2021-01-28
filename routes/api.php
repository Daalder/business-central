<?php

/**
 * Item -> product
 */
Route::post('item', 'NotifyController@createItem');
Route::delete('item', 'NotifyController@deleteItem');
Route::patch('item', 'NotifyController@updateItem');

/**
 * Customer -> customer
 */
Route::post('customer', 'NotifyController@createCustomer');
Route::patch('customer', 'NotifyController@updateCustomer');

/**
 * SalesOrder -> order
 */
//Route::post('sales-order', 'NotifyController@createSalesOrder');
//Route::patch('sales-order', 'NotifyController@updateSalesOrder');

Route::prefix('webhook')->group(function () {
    Route::post('subscription/{subscription}', 'SubscriptionController@index')->name('webhook.subscription.businesscentral');
});

