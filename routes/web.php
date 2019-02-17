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

Route::get('pay', 'PayController@index');
Route::get('code', 'PayController@code');
Route::get('login', 'PayController@login');
Route::get('oauth_callback', 'PayController@oauthCallback');
