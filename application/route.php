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
//require_once(__DIR__ . '/blog/route.php');

// API文档部分路由
Route::get('api/apis', 'api/store.v1.ApiDocs/getApiDocs');


// 公共接口部分
Route::group('/api/common/:version', function () {
	Route::post('/upload', 'api/common.:version.Common/uploadFile');
	Route::post('/log', 'api/common.:version.Common/uploadLog');
});

// 小商城模块接口路由
Route::group('api/store/:version', function () {

	// 获取轮播图
	Route::get('/banner/:id', 'api/store.:version.Banner/getBanner');

	// 获取精选主题
	Route::get('/theme', 'api/store.:version.Theme/getSimpleList');
	Route::get('/theme/:id', 'api/store.:version.Theme/getComplexOne');

	// 获取区域相关路由
	Route::group('/area', function () {
		Route::get('/list', 'api/store.:version.Area/getAreaList');
		Route::get('/all', 'api/store.:version.Area/getAllArea');
	});

	// 商品部分分类
	Route::group('/product', function () {
		Route::get('/all', 'api/store.:version.Product/getAllProduct');
		Route::get('/by_category', 'api/store.:version.Product/getAllInCategory');
		Route::get('/:id', 'api/store.:version.Product/getOne', [], ['id' => '\d+']);
		Route::get('/recent', 'api/store.:version.Product/getRecent');
	});

	// 商品分类部分路由
	Route::group('/category', function () {
		Route::get('/all', 'api/store.:version.Category/getAllCategories');
		Route::get('/pagination', 'api/store.:version.Category/getPaginationCategory');
		Route::post('/', 'api/store.:version.Category/addCategory');
		Route::put('/', 'api/store.:version.Category/updateCategory');
		Route::delete('/:id', 'api/store.:version.Category/delCategory');
	});

	// 获取Token路由
	Route::group('/token', function () {
		Route::post('/app', 'api/store.:version.Token/getAppToken');
		Route::post('/login', 'api/store.:version.Token/login');
		Route::post('/register', 'api/store.:version.Token/register');
		Route::post('/user', 'api/store.:version.Token/getToken');
		Route::post('/verify', 'api/store.:version.Token/verifyToken');
	});

	// 获取地址路由
	Route::group('/address', function () {
		Route::post('/', 'api/store.:version.Address/saveAddress');
		Route::get('/', 'api/store.:version.Address/getAddress');
	});

	// 获取订单的路由
	Route::group('/order', function () {
		Route::post('/', 'api/store.:version.Order/placeOrder');
		Route::get('/:id', 'api/store.:version.Order/getDetail', [], ['id' => '\d+']);
		Route::get('/by_user', 'api/store.:version.Order/getSummaryByUser');
		Route::get('/paginate', 'api/store.:version.Order/getSummary');
		Route::put('/delivery', 'api/store.:version.Order/delivery');
	});

	// 支付相关路由
	Route::group('/pay', function () {
		Route::post('/pre_order', 'api/store.:version.Pay/getPreOrder');
		Route::post('/notify', 'api/store.:version.Pay/receiveNotify');
		Route::post('/re_notify', 'api/store.:version.Pay/redirectNotify');
	});

	Route::post('/alipay/notify', 'api/store.:version.AliPay/receiveNotify');

});

//	cms 模块接口
Route::group('api/cms/:version', function () {
	//	用户模块接口路由
	Route::group('/user', function () {
		Route::get('/list', 'api/cms.:version.User/getAllUser');
		Route::get('/pagination', 'api/cms.:version.User/getPaginationUser');
	});

	Route::group('/test', function () {
		Route::get('/list', 'api/cms.:version.User/getApiName');
		Route::get('/list2', 'api/cms.:version.User/getApiName2');
	});
});


