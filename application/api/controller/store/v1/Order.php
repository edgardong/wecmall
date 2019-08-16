<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-27
 * Time: 14:01
 */

namespace app\api\controller\store\v1;

use app\api\controller\BaseController;
use app\api\service\Token as TokenService;
use app\api\service\Order as OrderService;
use app\api\model\Order as OrderModel;

use app\api\validate\IDMustBePositiveInt;
use app\api\validate\OrderPlace;
use app\api\validate\PagingParameter;
use app\lib\core\Result;
use app\lib\exception\OrderException;
use app\lib\exception\SuccessMessage;
use think\Log;

class Order extends BaseController
{

	protected $beforeActionList = [
		'checkExclusiveScope' => ['only' => 'placeOrder'],
		'checkPrimaryScope' => ['only' => 'getDetail,getSummaryByUser']
	];

	/**
	 * 下单
	 * @return array
	 * @throws \app\lib\exception\ParameterException
	 * @throws \app\lib\exception\TokenException
	 * @throws \think\Exception
	 */
	public function placeOrder()
	{
		(new OrderPlace())->goCheck();

		$products = input('post.products/a');
		$uid = TokenService::getCurrentUId();
		$order = new OrderService();
		$status = $order->place($uid, $products);
		return $status;
	}

	/**
	 * 获取订单列表信息
	 * @param int $page
	 * @param int $size
	 * @return array
	 * @throws \app\lib\exception\ParameterException
	 * @throws \app\lib\exception\TokenException
	 * @throws \think\Exception
	 */
	public function getSummaryByUser($page = 1, $size = 15)
	{
		(new PagingParameter())->goCheck();
		$uid = TokenService::getCurrentUId();
//		Log::record('getSummaryByUser' . $uid);
		$pagingOrders = OrderModel::getSummaryByUser($uid, $page, $size);
		if ($pagingOrders->isEmpty()) {
			return [
				'data' => [],
				'current_page' => $page,
			];
		}

		return [
			'data' => $pagingOrders->hidden(['snap_items', 'snap_address', 'prepay_id'])->toArray(),
			'current_page' => $page,
		];
	}

	/**
	 * 获取所有订单列表
	 * @param int $page
	 * @param int $size
	 * @return array
	 * @throws \app\lib\exception\ParameterException
	 */
	public function getAllOrder($page = 1, $size = 15)
	{
		(new PagingParameter())->goCheck();
		$orders = OrderModel::getSummaryByPage($page, $size);

		return ['data' => $orders,
			'current_page' => $page];
	}

	/**
	 * 获取订单详情
	 * @param $id
	 * @return mixed
	 * @throws OrderException
	 * @throws \app\lib\exception\ParameterException
	 */
	public function getDetail($id)
	{
		(new IDMustBePositiveInt())->goCheck();

		$orderDetail = OrderModel::get($id);

		if (!$orderDetail) {
			throw new OrderException();
		}
		return $orderDetail->hidden(['prepay_id']);
	}

	/**
	 * 获取全部订单的简要信息
	 * @param int $page
	 * @param int $size
	 * @return array
	 * @throws \app\lib\exception\ParameterException
	 */
	public function getSummary($page = 1, $size = 20)
	{
		(new PagingParameter())->goCheck();

		$pagingOrders = OrderModel::getSummaryByPage($page, $size);
		if ($pagingOrders->isEmpty()) {
			return [
				'current_page' => $page,
				'data' => []
			];
		}
		$data = $pagingOrders->hidden(['snap_items', 'snap_address'])
			->toArray();

		return Result::ok($data);
	}

	/**
	 * 发送模版信息
	 * @param $id
	 * @return SuccessMessage
	 * @throws OrderException
	 * @throws \app\lib\exception\ParameterException
	 * @throws \think\db\exception\DataNotFoundException
	 * @throws \think\db\exception\ModelNotFoundException
	 * @throws \think\exception\DbException
	 */
	public function delivery($id)
	{
		(new IDMustBePositiveInt())->goCheck();
		$order = new OrderService();
		$success = $order->delivery($id);
		if ($success) {
			return new SuccessMessage();
		}
	}

}