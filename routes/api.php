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
//用户相关-------------------
Route::group(['middleware'=>['auth:api'],"prefix"=>"admin"],function() {
    //基本操作
    Route::resource('base','Api\Controller\AdminController');
    //获取当前用户
    Route::get('getUser',function(Request $request){
        return $request->user();
    });
    //获取用户列表
    Route::get('userList','Api\Controller\AdminController@userList');
});

//用户权限
Route::group(['middleware' => ['auth:api'],"prefix"=>"authRule"], function () {
    //基本操作
    Route::resource('base','Api\Controller\AuthRuleController');
    //用户菜单
    Route::get('menu','Api\Controller\AuthRuleController@getMenu');
    //获取所有权限树
    Route::get('getPermissions','Api\Controller\AuthRuleController@getPermissions');
});

//用户组
Route::group(['middleware' => ['auth:api'],"prefix"=>"authGroup"], function () {
    //基本操作
    Route::resource('base','Api\Controller\AuthGroupController');
    //获取用户组列表
    Route::get('authGroupList','Api\Controller\AuthGroupController@authGroupList');
});

//用户组认证
Route::group(['middleware' => ['auth:api'],"prefix"=>"authGroupAccess"], function () {
    //基本操作
    Route::resource('base','Api\Controller\AuthGroupAccessController');
});
