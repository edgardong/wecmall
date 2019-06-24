<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-05-23
 * Time: 16:34
 */

namespace app\api\model;


class TbMember extends BaseModel
{
	public static function check($ac, $se)
	{
		$app = self::where('username', '=', $ac)
			->where('password', '=', $se)
			->find();
		return $app;
	}

	public function address()
	{
		return $this->hasOne('UserAddress', 'user_id', 'id');
	}
}