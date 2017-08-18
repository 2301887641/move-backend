<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//获取用户信息
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
//
Route::group(['middleware' => ['auth:api']], function () {
    //用户相关-------------------
    Route::resource('admin','Api\Controller\AdminController');
    //获取当前用户
    Route::get('/admin/getUser',function(Request $request){
        return $request->user();
    });
    //批量删除
    Route::post('/admin/delAll','Api\Controller\AdminController@destroyAll');
});

