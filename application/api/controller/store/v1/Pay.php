<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-03-05
 * Time: 21:26
 */

namespace app\api\controller\store\v1;

use think\App;

use app\api\controller\BaseController;
use app\api\service\WxNotify;
use app\api\validate\IDMustBePositiveInt;

use app\api\service\Pay as PayService;
use think\Loader;
use think\Request;

//require_once App::getRootPath() . 'extend/WxPay/WxPay.Api.php';

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');

class Pay extends BaseController
{
	// 定义权限
	protected $beforeActionList = [
		'checkExclusiveScope' => ['only' => 'getPreOrder']
	];

	/**
	 * 请求预订单
	 * @param string $id	预订单的ID
	 * @param string $type	支付类型
	 * @throws
	 * @return mixed
	 */
	public function getPreOrder($id = '', $type = 'wechat')
	{
		(new IDMustBePositiveInt())->goCheck();
		$source = Request::instance()->header('source');
		$pay = new PayService($id, $source, $type);
		return $pay->pay();
	}

	/**
	 * 接受微信回掉信息
	 */
	public function receiveNotify()
	{
		// 1. 检查库存量， 超卖
		// 2. 更新订单状态
		// 3. 减少库存量
//    $notify = new WxNotify();
//    $notify->HandleNotify();

		$xmlData = file_get_contents('php://input');
		$result = curl_post_raw('http://www.edgarhao.cn/api/v1/pay/re_notify?XDEBUG_SESSION_START=19193',
			$xmlData);
	}

	/**
	 * 接受微信回掉信息
	 */
	public function redirectNotify()
	{
		// 1. 检查库存量， 超卖
		// 2. 更新订单状态
		// 3. 减少库存量
		$notify = new WxNotify();
		$notify->HandleNotify();
	}

}