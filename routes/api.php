<?php

use vendor\App\Router\Route;

Route::get('/orders', 'Api\OrderController@index')->middleware('auth');
Route::get('/orders/{id}', 'Api\OrderController@show');

Route::post('/orders', 'Api\OrderController@create');
Route::post('/orders/{id}/items', 'Api\OrderController@add');
Route::post('/orders/{id}/done', 'Api\OrderController@done')->middleware('auth');