<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-21
 * Time: 11:31
 */

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\model\Category as CategoryModel;
use app\lib\exception\CategoryException;
use app\api\validate\Category as CategoryValidate;
use think\Log;

class Category extends BaseController
{

	/** 获取全部分类
	 * @return mixed
	 * @throws
	 */
	public function getAllCategories()
	{
		$categories = CategoryModel::all([], 'img');

		if ($categories->isEmpty()) {
			throw new CategoryException();
		}
		return $categories;
	}

	public function  getPaginationCategory(){

	}

	/**
	 * 更新分类名称
	 * @throws
	 */
	public function updateCategory()
	{
		$validate = new CategoryValidate();
		$arrayData = $validate->getDataByRule(input('put.'));
		Log::record($arrayData);
		$result = CategoryModel::update($arrayData, ['id', '=', $arrayData['id']]);
		return $result;
	}

	/***
	 * 添加分类
	 * @return CategoryModel
	 * @throws
	 */
	public function addCategory()
	{
		$validate = new CategoryValidate();
		$arrayData = $validate->getDataByRule(input('post.'));
		// 添加图片到Image路由里
		$result = CategoryModel::create($arrayData);
		return $result;
	}

	/**
	 * 删除分类
	 * @param $id
	 * @return int
	 */
	public function delCategory($id)
	{
		$result = CategoryModel::where('id', '=', $id)->delete();
		return $result;
	}
}