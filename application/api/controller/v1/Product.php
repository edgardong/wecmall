<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-21
 * Time: 10:28
 */

namespace app\api\controller\v1;


use app\api\validate\Count;
use app\api\model\Product as ProductModel;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\PagingParameter;
use app\lib\core\Result;
use app\lib\exception\ProductException;

class Product
{
	/**
	 * 获取最新新品
	 * @param int $count
	 * @throws
	 * @return mixed
	 */
	public function getRecent($count = 15)
	{
		(new Count())->goCheck();

		$products = ProductModel::getMostRecent($count);

		if ($products->isEmpty()) {
			throw new ProductException();
		}

		return $products;
	}

	/**
	 * 获取所有产品列表
	 * @param int $page
	 * @param int $size
	 * @return
	 * @throws \app\lib\exception\ParameterException
	 * @throws \think\exception\DbException
	 */
	public function getAllProduct($page = 1, $size = 15)
	{
		(new PagingParameter())->goCheck();
		$products = ProductModel::getAllProduct($page, $size);
		return Result::ok($products);
	}

	/**
	 * 获取分类下的所有产品
	 * @param $id
	 * @return mixed
	 * @throws
	 */
	public function getAllInCategory($id)
	{
		(new IDMustBePositiveInt())->goCheck();

		$products = ProductModel::getProductsByCategory($id);

		if ($products->isEmpty()) {
			throw new ProductException();
		}
		return $products;
	}


	/**
	 * 获取商品详情
	 * @param $id
	 * @return array|\PDOStatement|string|\think\Model|null
	 * @throws
	 */
	public function getOne($id)
	{
		(new IDMustBePositiveInt())->goCheck();

		$product = ProductModel::getProductDetail($id);

		if (!$product) {
			throw  new ProductException();
		}
		return $product;
	}

	public function deleteOne($id)
	{

	}
}