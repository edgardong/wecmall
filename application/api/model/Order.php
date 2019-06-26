<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-03-02
 * Time: 13:44
 */

namespace app\api\model;


class Order extends BaseModel
{

  protected $hidden = ['user_id', 'delete_time', 'update_time'];

  protected $autoWriteTimestamp = true;

  protected $createTime = 'create_time';
  protected $updateTime = 'update_time';

  /**
   * 读取器
   * @param $value
   * @return mixed|null
   */
  public function getSnapAddressAttr($value)
  {
    if (empty($value)) {
      return null;
    }
    return json_decode($value);
  }

  /**
   * 读取器
   * @param $value
   * @return mixed|null
   */
  public function getSnapItemsAttr($value)
  {
    if (empty($value)) {
      return null;
    }
    return json_decode($value);
  }

  /**
   * 获取用户订单列表
   * @param $uid
   * @param int $page
   * @param int $size
   * @return \think\Paginator
   * @throws \think\exception\DbException
   */
  public static function getSummaryByUser($uid, $page = 1, $size = 10)
  {
//    Paginator::
    $pagingData = self::where('user_id', '=', $uid)
      ->order('create_time desc')
      ->paginate($size, true, ['page' => $page]);

    return $pagingData;
  }

  public static function getSummaryByPage($page, $size)
  {
    $pagingData = self::order('create_time desc')
      ->paginate($size, false, ['page' => $page]);

    return $pagingData;
  }
}