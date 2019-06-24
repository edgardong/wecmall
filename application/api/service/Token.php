<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-22
 * Time: 15:06
 */

namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Exception;
use think\Cache;
use think\Log;

class Token
{
	/**
	 * 生成token
	 * @return string
	 */
	protected static function generateToken()
	{
		// 32 个字符串
		$randChars = getRandChars(32);
		$timestamp = $_SERVER['REQUEST_TIME'];
		$salt = config('secure.token_salt');
		// 用三组字符串进行MD5加密，加密后作为token
		return md5($randChars . $timestamp . $salt);
	}


	/**
	 * 获取token中的值
	 * @param $key
	 * @return mixed
	 * @throws Exception
	 * @throws TokenException
	 */
	public static function getCurrentTokenVar($key)
	{
		$token = request()
			->header('token');
		$vars = Cache::get($token);
		if (!$vars) {
			throw new TokenException();
		} else {
			Log::record($vars);
			if (!is_array($vars)) {
				$vars = json_decode($vars, true);
			}
			// 返回值
			if (array_key_exists($key, $vars)) {
				return $vars[$key];
			} else {
				throw new Exception('尝试获取的Token变量不存在');
			}
		}
	}

	/**
	 * 获取当前用户的ID
	 * @return mixed
	 * @throws Exception
	 * @throws TokenException
	 */
	public static function getCurrentUId()
	{
		return self::getCurrentTokenVar('uid');
	}

	/**
	 * @return bool
	 * @throws Exception
	 * @throws ForbiddenException
	 * @throws TokenException
	 */
	public static function needPrimaryScope()
	{
		$scope = self::getCurrentTokenVar('scope');
		if ($scope) {

			if ($scope >= ScopeEnum::User) {
				return true;
			} else {
				throw  new ForbiddenException();
			}
		} else {
			throw new TokenException();
		}
	}

	/**
	 * 校验用户操作权限
	 * @return bool
	 * @throws ForbiddenException
	 * @throws TokenException
	 * @throws \think\Exception
	 */
	public static function needExclusiveScope()
	{
		$scope = self::getCurrentTokenVar('scope');
		if ($scope) {

			if ($scope == ScopeEnum::User) {
				return true;
			} else {
				throw  new ForbiddenException();
			}
		} else {
			throw new TokenException();
		}
	}

	/**
	 * 检查操作是否合法
	 * @param $checkedUId
	 * @return bool
	 * @throws Exception
	 * @throws TokenException
	 */
	public static function isValidOperate($checkedUId)
	{
		if (!$checkedUId) {
			throw new Exception("检查UID时必须存在UID");
		}

		$currentOperateUid = self::getCurrentUId();

		return $checkedUId == $currentOperateUid;
	}

	/**
	 * 校验token是否有效
	 * @param $token
	 * @return mixed
	 */
	public static function verifyToken($token)
	{
		$exist = Cache::get($token);
		return $exist;
	}
}