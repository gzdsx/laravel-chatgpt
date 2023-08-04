<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/post.php';
//首页
Route::get('/', 'OpenAi\IndexController@index');
Route::any('/test', 'Test\IndexController@index');
