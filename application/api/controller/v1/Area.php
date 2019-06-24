<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-03-22
 * Time: 14:29
 */

namespace app\api\controller\v1;

use app\api\model\TArea as AreaModel;

use app\api\controller\BaseController;
use app\lib\exception\AreaException;
use think\Log;

class Area extends BaseController
{
  /**
   * 根据父元素获取下面的区域
   * @param int $id
   * @return false|\PDOStatement|string|\think\Collection
   * @throws AreaException
   * @throws \app\lib\exception\ParameterException
   * @throws \think\db\exception\DataNotFoundException
   * @throws \think\db\exception\ModelNotFoundException
   * @throws \think\exception\DbException
   */
  public function getAreaList($id = -1)
  {
//    (new IDMustBePositiveInt())->goCheck();

    $areas = AreaModel::where('parentId', '=', $id)
      ->select();

    if ($areas->isEmpty()) {
      throw new AreaException();
    }

    return $areas;
  }

  /**
   * 按照一定的规律（支付宝小程序级联选择格式）获取全部的省市县数据
   * @return array|mixed
   * @throws \think\db\exception\DataNotFoundException
   * @throws \think\db\exception\ModelNotFoundException
   * @throws \think\exception\DbException
   */
  public function getAllArea()
  {
    $data = cache('base_area');
    if (!$data) {

      $areas = AreaModel::order('parentId', 'desc')
        ->select()->toArray();
      $data = [];

      for ($j = 0; $j < count($areas); $j++) {
        $areas[$j]['name'] = $areas[$j]['areaName'];

        if ($areas[$j]['parentId'] == -1) {
          array_push($data, $areas[$j]);
        } else {
          // 获取父级区域的索引
          $index = $this->getParentFromData($areas, $areas[$j]['parentId']);
          if (!key_exists('subList', $areas[$index])) {
            $areas[$index]['subList'] = [];
          }
          array_push($areas[$index]['subList'], $areas[$j]);
        }
      }
      cache('base_area', $data);
    }

    return $data;
  }

  /**
   * 获取父级元素的索引
   * @param $data
   * @param $id
   * @return mixed
   */
  private function getParentFromData($data, $id)
  {
    for ($i = 0; $i < count($data); $i++) {
      if ($data[$i]['areaId'] == $id) {
        return $i;
      }
    }
  }
}