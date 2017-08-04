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
//生成验证码
Route::get('/captcha','Api\Controller\LoginController@getCaptcha');
//检查验证码
Route::get('/checkCaptcha/{captcha}','Api\Controller\LoginController@checkCaptcha');




Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
