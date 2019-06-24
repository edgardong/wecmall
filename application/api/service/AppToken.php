<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-03-20
 * Time: 17:26
 */

namespace app\api\service;


use app\api\model\TbMember;
use app\api\model\ThirdApp;
use app\lib\enum\ScopeEnum;
use app\lib\enum\UserTypeEnum;
use app\lib\exception\TokenException;

class AppToken extends Token
{
	/**
	 * 获取用户Token
	 * @param $ac
	 * @param $se
	 * @return string
	 * @throws TokenException
	 */
	public function get($ac, $se)
	{
		$app = ThirdApp::check($ac, $se);

		if (!$app) {
			throw new TokenException([
				'msg' => '授权失败',
				'errorCode' => 10004
			]);
		} else {
			$scope = $app->scope;
			$uid = $app->id;
			$values = [
				'type' => UserTypeEnum::ThirdApp,
				'scope' => $scope,
				'uid' => $uid
			];
			$token = $this->saveToCache($values);
			return $token;
		}
	}

	/**
	 * 校验登录是否正确
	 * @param $username
	 * @param $password
	 * @return array
	 * @throws TokenException
	 */
	public function checkLogin($username, $password)
	{
		$app = TbMember::check($username, $password);
		if (!$app) {
			return [
				'token' => '用户名或者密码错误',
				'errorCode' => 201
			];
		} else {
//			$scope = $app->scope;
			$scope = ScopeEnum::User;
			$uid = $app->id;
			$values = [
				'type' => UserTypeEnum::Member,
				'scope' => $scope,
				'uid' => $uid
			];
			$token = $this->saveToCache($values);

			return [
				'token' => $token,
				'errorCode' => 200
			];
		}
	}

	/**
	 * 保存到服务器中
	 * @param $value
	 * @return string
	 * @throws TokenException
	 */
	private function saveToCache($value)
	{
		$token = self::generateToken();
		$expire_in = config('setting.token_expire_in');
		$result = cache($token, json_encode($value), $expire_in);
		if (!$result) {
			throw new TokenException([
				'msg' => '服务器缓存异常',
				'errorCode' => 10005
			]);
		}
		return $token;
	}
}