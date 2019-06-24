<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-03-12
 * Time: 13:36
 */

namespace app\api\service;

use app\api\model\Order as OrderModel;
use app\api\model\Product;
use app\api\service\Order as OrderService;
use app\lib\enum\OrderStatusEnum;
use think\Db;
use think\Exception;
use think\Log;
use think\facade\App;

require_once App::getRootPath() . 'extend/WxPay/WxPay.Api.php';
require_once App::getRootPath() . 'extend/WxPay/WxPay.Notify.php';


class WxNotify extends \WxPayNotify
{

  public function HandleNotify()
  {
    $config = new \WxPayConfig();
    self::Handle($config);
  }

  public function NotifyProcess($objData, $config, &$msg)
  {
    if ($objData['return_code'] == 'SUCCESS') {
      $orderNo = $objData['out_trade_no'];
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
    } else {
      return true;
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
//      $singlePStatus['count']
      Product::where('id', '=', $singlePStatus['id'])
        ->setDec('stock', $singlePStatus['counts']);
    }
  }
}