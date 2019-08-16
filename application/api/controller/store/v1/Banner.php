<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-01-31
 * Time: 16:03
 */

namespace app\api\controller\store\v1;

use app\api\validate\IDMustBePositiveInt;
use app\api\model\Banner as BannerModel;
use app\lib\exception\BannerMissException;

/**
 * Class Banner 轮播图类
 * @package app\api\controller\v1
 */
class Banner
{

  /**
   * 获取轮播图
   * @url /banner/:id
   * @http GET
   * @params $id Banner的Id号
   * @throws
   * @return mixed object 轮播图数据
   */
  public function getBanner($id)
  {

    (new IDMustBePositiveInt())->goCheck();
    // 获取轮播图
    $banner = BannerModel::getBannerById($id);
    //  $banner = BannerModel::with(['items', 'items.img'])->find($id);
    //  $banner->hidden(['update_time', 'delete_time']);
    //  $data = $banner->toArray();
    //  unset($data['delete_time']);

    if (!$banner) {
      throw new BannerMissException();
    }
    return $banner;
  }
}