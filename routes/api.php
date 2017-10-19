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
    //用户权限
    Route::resource('authRule','Api\Controller\AuthRuleController');
    //用户菜单
    Route::get('/menu','Api\Controller\AuthRuleController@getMenu');
    //获取所有权限树
    Route::get('/getPermissions','Api\Controller\AuthRuleController@getPermissions');
    //用户角色
    Route::resource('authGroup','Api\Controller\AuthGroupController');
});

