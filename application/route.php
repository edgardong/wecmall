<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

// 引入 blog模块下的路由
require_once(__DIR__.'/blog/route.php');

// API文档部分路由
Route::get('api/apis', 'api/v1.ApiDocs/getApiDocs');

// 获取轮播图
Route::get('api/:version/banner/:id', 'api/:version.Banner/getBanner');
// 获取精选主题
Route::get('api/:version/theme', 'api/:version.Theme/getSimpleList');
Route::get('api/:version/theme/:id', 'api/:version.Theme/getComplexOne');

//Route::get('api/:version/product/by_category', 'api/:version.Product/getAllInCategory');
//Route::get('api/:version/product/:id', 'api/:version.Product/getOne', [], ['id' => '\d+']);
//Route::get('api/:version/product/recent', 'api/:version.Product/getRecent');

// 商品部分分类
Route::group('api/:version/product', function () {
	Route::get('/by_category', 'api/:version.Product/getAllInCategory');
	Route::get('/:id', 'api/:version.Product/getOne', [], ['id' => '\d+']);
	Route::get('/recent', 'api/:version.Product/getRecent');
});

// 商品分类部分路由
Route::get('api/:version/category/all', 'api/:version.Category/getAllCategories');
Route::post('api/:version/category', 'api/:version.Category/addCategory');
Route::put('api/:version/category', 'api/:version.Category/updateCategory');
Route::delete('api/:version/category/:id', 'api/:version.Category/delCategory');

// 获取Token路由
Route::post('api/:version/token/app', 'api/:version.Token/getAppToken');
Route::post('api/:version/token/login', 'api/:version.Token/login');
Route::post('api/:version/token/register', 'api/:version.Token/register');
Route::post('api/:version/token/user', 'api/:version.Token/getToken');
Route::post('api/:version/token/verify', 'api/:version.Token/verifyToken');

// 获取地址路由
Route::post('api/:version/address', 'api/:version.Address/saveAddress');
Route::get('api/:version/address', 'api/:version.Address/getAddress');

// 获取订单的路由
Route::post('api/:version/order', 'api/:version.Order/placeOrder');
Route::get('api/:version/order/:id', 'api/:version.Order/getDetail', [], ['id' => '\d+']);
Route::get('api/:version/order/by_user', 'api/:version.Order/getSummaryByUser');
Route::get('api/:version/order/paginate', 'api/:version.Order/getSummary');
Route::put('api/:version/order/delivery', 'api/:version.Order/delivery');

// 支付相关路由
Route::post('api/:version/pay/pre_order', 'api/:version.Pay/getPreOrder');
Route::post('api/:version/pay/notify', 'api/:version.Pay/receiveNotify');
Route::post('api/:version/pay/re_notify', 'api/:version.Pay/redirectNotify');


Route::post('api/:version/alipay/notify', 'api/:version.AliPay/receiveNotify');


// 获取区域相关路由
Route::get('api/:version/area/list', 'api/:version.Area/getAreaList');
Route::get('api/:version/area/all', 'api/:version.Area/getAllArea');

// 公共接口部分
Route::post('api/:version/common/upload', 'api/:version.Common/uploadFile');
Route::post('api/:version/common/log', 'api/:version.Common/uploadLog');


