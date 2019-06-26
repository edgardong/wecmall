<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-06-26
 * Time: 14:48
 */

namespace app\lib\core;


class Result
{

	/**
	 * 返回正常的模型
	 * @param $data
	 * @param string $msg
	 * @return array
	 */
	public static function ok($data, $msg = '操作成功')
	{
		return self::setResult($data, 0, $msg);
	}

	/**
	 * 返回提示类信息
	 * @param $data
	 * @param $code
	 * @param string $msg
	 * @return array
	 */
	public static function info($data, $code, $msg = '')
	{
		return self::setResult($data, $code, $msg);
	}

	/**
	 * 返回错误类信息
	 * @param $data
	 * @param string $msg
	 * @return array
	 */
	public static function error($data, $msg = '操作失败')
	{
		return self::setResult($data, 50001, $msg);
	}

	private static function setResult($data, $code, $msg)
	{
		return [
			'data' => $data,
			'errCode' => $code,
			'errMsg' => $msg
		];
	}
}