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
//api生成验证码
Route::get('/captcha','Api\LoginController@getCaptcha');
//api检查验证码
Route::get('/checkCaptcha/{captcha}','Api\LoginController@checkCaptcha');
//测试
Route::get('/menu','Api\Controller\AuthRuleController@getMenu');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
