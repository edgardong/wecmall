<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-21
 * Time: 11:32
 */

namespace app\api\model;


class Category extends BaseModel
{

	/**
	 * 图片读取器
	 * @return \think\model\relation\BelongsTo
	 */
	public function img()
	{
		return $this->belongsTo('Image', 'topic_img_id', 'id');
	}

	/**
	 * 获取所有类别
	 * @param int $page
	 * @param int $size
	 * @return \think\Paginator
	 * @throws \think\exception\DbException
	 */
	public static function getAllCategory($page, $size)
	{
		$data = self::with('img')
			->order('create_time desc')
			->paginate($size, false, ['page' => $page]);

		return $data;
	}

}