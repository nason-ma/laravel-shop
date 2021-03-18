<?php

use App\Admin\Controllers\UsersController;
use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    $router->get('users', 'UsersController@index')->name('users.index');

    $router->get('products', 'ProductsController@index')->name('products.index');
    $router->get('products/create', 'ProductsController@create')->name('products.create');
    $router->post('products', 'ProductsController@store')->name('products.store');
    $router->get('products/{id}/edit', 'ProductsController@edit')->name('products.edit');
    $router->put('products/{id}', 'ProductsController@update')->name('products.update');

    $router->get('orders', 'OrdersController@index')->name('orders.index');
    $router->get('orders/{order}', 'OrdersController@show')->name('orders.show');
    $router->post('orders/{order}/ship', 'OrdersController@ship')->name('orders.ship');
    $router->post('orders/{order}/refund', 'OrdersController@handleRefund')->name('orders.handle_refund');

    $router->get('coupon_codes', 'CouponCodesController@index');
});
