<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-05-30
 * Time: 16:00
 */

namespace app\api\controller\store\v1;

use app\api\model\Order as OrderModel;
use app\api\model\Product;
use app\api\service\Order as OrderService;
use app\api\controller\BaseController;
use app\lib\enum\OrderStatusEnum;
use think\Db;
use think\Exception;
use think\Log;

class Alipay extends BaseController
{

	/**
	 * 获取服务通知
	 * @return bool
	 * @throws \think\db\exception\DataNotFoundException
	 * @throws \think\db\exception\ModelNotFoundException
	 * @throws \think\exception\DbException
	 * @throws \think\exception\PDOException
	 */
	public function receiveNotify()
	{
		$data = input('post.');

		$result = [
			'gmt_create' => '2019-05-30 16:11:32',
			'charset' => 'UTF-8',
			'seller_email' => '2535615874@qq.com',
			'subject' => 'App支付测试',
			'sign' => 'cKepe6r9hZM4xr54mZg7Zrocl25CDcfrvEUgRga9N14urG7RTFXDKyMQa9pz+KuwCBbJqvTFDYRRuskyl5CI8Xzn+zlPsUft/uvFVabiDtBuMHMHJACmgzxrYH1YCB1UQlxEj/9f/8/Xq/Jd45jPRioWxplXUjVhQCOBItvZLP8s1e3GSNX1kVHrEJuh6Q1OGKrgbHvdGPFl9hXV6vBk4jN6OwL1owhRgy5+XO5MXkFZExbupEbt7UPcc4KbKY00imvKtOEKPhvaLt+QU3idz2l/g7J18WQ1Nenr3IsyO/VE0hr/4pCVZ0jPo9Mcr6SsdlFPcnQFqtexqDz9L2NU5A==',
			'body' => '破冰商城--小商品',
			'buyer_id' => '2088222453131177',
			'invoice_amount' => '0.02',
			'notify_id' => '2019053000222161133031170537909240',
			'fund_bill_list' => '[{"amount":"0.02","fundChannel":"ALIPAYACCOUNT"}]',
			'notify_type' => 'trade_status_sync',
			'trade_status' => 'TRADE_SUCCESS',
			'receipt_amount' => '0.02',
			'app_id' => '2019052965370751',
			'buyer_pay_amount' => '0.02',
			'sign_type' => 'RSA2',
			'seller_id' => '2088802143048200',
			'gmt_payment' => '2019-05-30 16:11:33',
			'notify_time' => '2019-05-30 16:11:33',
			'version' => '1.0',
			'out_trade_no' => 'A530853436046260',
			'total_amount' => '0.02',
			'trade_no' => '2019053022001431170503661888',
			'auth_app_id' => '2019052965370751',
			'buyer_logon_id' => '221***@qq.com',
			'point_amount' => '0.00',
		];

		if ($data['trade_status'] == 'TRADE_SUCCESS') {
			// 支付成功，修改库存量
			$orderNo = $data['out_trade_no'];
			try {
				Db::startTrans();
				$order = OrderModel::where('order_no', '=', $orderNo)
					->lock(true)
					->find();
				// 未支付订单
				if ($order->status == 1) {
					$service = new OrderService();
					// 检查订单中商品的库存量
					$stockStatus = $service->checkOrderStock($order->id);
					if ($stockStatus['pass']) {
						// 更新订单状态
						$this->updateOrderStatus($order->id, true);
						// 减少库存量
						$this->reduceStock($stockStatus);
					} else {
						$this->updateOrderStatus($order->id, false);
					}
				}
				Db::commit();
				return true;
			} catch (Exception $ex) {
				Log::error($ex);
				Db::rollback();
				return false;
			}
		}
	}

	/**
	 * 更新订单状态
	 * @param $orderId
	 * @param $success
	 * @throws Exception
	 * @throws \think\exception\PDOException
	 */
	private function updateOrderStatus($orderId, $success)
	{
		$status = $success ?
			OrderStatusEnum::PAID :
			OrderStatusEnum::PAID_BUT_OUT_OF;

		OrderModel::where('id', '=', $orderId)
			->update(['status' => $status]);
	}

	/**
	 * 减少库存
	 * @param $stockStatus
	 * @throws Exception
	 */
	private function reduceStock($stockStatus)
	{
		foreach ($stockStatus['pStatusArray'] as $singlePStatus) {
			Product::where('id', '=', $singlePStatus['id'])
				->setDec('stock', $singlePStatus['counts']);
		}
	}
}