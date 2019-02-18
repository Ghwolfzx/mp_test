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

// 小程序
Route::get('mini/login', 'MiniProgramController@login');

Route::get('pay', 'PayController@index');
Route::get('login', 'PayController@login');
Route::get('oauth_callback', 'PayController@oauthCallback');
