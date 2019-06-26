<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-19
 * Time: 22:15
 */

namespace app\api\model;


use app\lib\core\Result;

class Product extends BaseModel
{
	// 设置隐藏字段 pivot think5框架针对多对多数据自带的
	protected $hidden = ['update_time', 'imgs1', 'delete_time', 'pivot', 'create_time', 'from', 'category_id'];

	// 设置图片的完整路径
	public function getMainImgUrlAttr($value, $data)
	{
		return $this->prefixImgUrl($value, $data);
	}

	/**
	 * 获取最近新品
	 * @param $count
	 * @throws
	 * @return mixed
	 */
	public static function getMostRecent($count)
	{
		$product = self::limit($count)
			->order('create_time desc')
			->select();

		return $product;
	}

	public function imgs1()
	{
		return $this->hasMany('ProductImage', 'product_id', 'id');
	}

	public function imgs()
	{
		return $this->hasMany('ProductImage', 'product_id', 'id');
	}

	public function properties()
	{
		return $this->hasMany('ProductProperty', 'product_id', 'id');
	}


	/**
	 * 获取所有产品列表
	 * @param $page
	 * @param $size
	 * @return
	 * @throws \think\exception\DbException
	 */
	public static function getAllProduct($page, $size)
	{
		$products = self::order('create_time desc')
			->paginate($size, false, ['page' => $page]);
		return $products;
	}

	/**
	 * 获取分类下的所有产品
	 * @param $id
	 * @return array|\PDOStatement|string|\think\Collection
	 * @throws \think\db\exception\DataNotFoundException
	 * @throws \think\db\exception\ModelNotFoundException
	 * @throws \think\exception\DbException
	 */
	public static function getProductsByCategory($id)
	{
		$products = self::where('category_id', '=', $id)
			->select();
		return $products;
	}

	/**
	 * 获取商品详情
	 * @param $id
	 * @return array|\PDOStatement|string|\think\Model|null
	 * @throws \think\db\exception\DataNotFoundException
	 * @throws \think\db\exception\ModelNotFoundException
	 * @throws \think\exception\DbException
	 */
	public static function getProductDetail($id)
	{
//    with([
//      'imgs' => function ($query) {
//        $query->with(['imgUrl'])->order('order', 'asc');
//      }
//    ])
//      ->


		$product = self::with(
			[
				'imgs' => function ($query) {
					$query->with(['imgUrl'])
						->order('order', 'asc');
				}])
			->with('properties')
			->find($id);

//    $product = self::with(['imgs1.ImgUrl', 'properties'])
//      ->find($id);
//    // $product.imgs.order('order asc');
//    $imgs = json_decode($product->imgs1, true);
//    // 自定义排序
//    usort($imgs, function ($a, $b) {
//      return $a['order'] > $b['order'];
//    });
//    $product->imgs = $imgs;
		return $product;
	}
}