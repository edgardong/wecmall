<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-01-31
 * Time: 17:45
 */

namespace app\api\validate;

use think\Request;
use think\Validate;
use \app\lib\exception\ParameterException;

class BaseValidate extends Validate
{
	/**
	 * 验证器校验方法
	 * @throws
	 */
	public function goCheck()
	{
		// 获取http传入的参数
		// 对参数进行校验
		$request = Request::instance();
		$params = $request->param();
		$params['token'] = $request->header('token');

		if (!$this->check($params)) {
			$exception = new ParameterException(
				[
					// $this->error有一个问题，并不是一定返回数组，需要判断
					'msg' => is_array($this->error) ? implode(
						';', $this->error) : $this->error,
				]);
			throw $exception;
		}

		return true;

//    // 参数校验
//    $result = $this->batch()->check($params);
//
//    // 验证失败，抛出异常；否则返回true
//    if (!$result) {
//      $e = new ParameterException([
//        'msg' => $this->error
//      ]);
//      throw $e;
//    } else {
//      return true;
//    }

	}

	/**
	 * 判断是否为正整数
	 * @param $value
	 * @param string $rule
	 * @param string $data
	 * @param string $field
	 * @return bool
	 */
	protected function isPositiveInteger($value, $rule = '', $data = '', $field = '')
	{
		if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
			return true;
		} else {
//      return $field . " 必须是正整数";
			return false;
		}
	}


	/**
	 * 判断传入的值是否为空
	 * @param $value
	 * @return bool
	 */
	public function isNotEmpty($value)
	{
		if (empty($value)) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * @param array $arrays 通常传入request.post变量数组
	 * @return array 按照规则key过滤后的变量数组
	 * @throws ParameterException
	 */
	public function getDataByRule($arrays)
	{
		if (array_key_exists('user_id', $arrays) | array_key_exists('uid', $arrays)) {
			// 不允许包含user_id或者uid，防止恶意覆盖user_id外键
			throw new ParameterException([
				'msg' => '参数中包含有非法的参数名user_id或者uid'
			]);
		}
		$newArray = [];
		foreach ($this->rule as $key => $value) {
			if (array_key_exists($key, $arrays)) {
				$newArray[$key] = $arrays[$key];
			}
		}
		return $newArray;
	}

	//没有使用TP的正则验证，集中在一处方便以后修改
	//不推荐使用正则，因为复用性太差
	//手机号的验证规则
	protected function isMobile($value)
	{
		$rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
		$result = preg_match($rule, $value);
		if ($result) {
			return true;
		} else {
			return false;
		}
	}
}