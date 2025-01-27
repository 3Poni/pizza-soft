<?php

use vendor\App\Router\Route;

Route::get('/api/v1/orders', 'Api\OrderController@index')->middleware('auth');
Route::get('/api/v1/orders/{id}', 'Api\OrderController@show');

Route::post('/api/v1/orders', 'Api\OrderController@create');
Route::post('/api/v1/orders/{id}/items', 'Api\OrderController@add');
Route::post('/api/v1/orders/{id}/done', 'Api\OrderController@done')->middleware('auth');