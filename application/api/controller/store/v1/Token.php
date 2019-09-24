<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-22
 * Time: 13:18
 */

namespace app\api\controller\store\v1;

use app\api\model\TbMember;
use app\api\service\AppToken;
use app\api\service\UserToken;
use app\api\service\Token as TokenService;
use app\api\validate\AppTokenGet;
use app\api\validate\TokenGet;
use app\api\validate\UserNew;
use app\lib\exception\ParameterException;
use think\Log;
use think\Request;

class Token
{
	/**
	 * 获取用户token
	 * @param string $code
	 * @throws
	 * @return mixed
	 */
	public function getToken($code = '')
	{
		(new TokenGet())->goCheck();
		// 根据来源获取不同的token
		$type = Request::instance()->header('source');
		$ut = new UserToken($code, $type);
		$token = $ut->get();

		return [
			'token' => $token
		];
	}

	public function verifyToken($token = '')
	{
		if (!$token) {
			throw new ParameterException([
				'token不能为空'
			]);
		}

		$valid = TokenService::verifyToken($token);

		return [
			'isValid' => $valid
		];

	}

	/**
	 * 获取第三方应用的token
	 * @param string $ac
	 * @param string $se
	 * @return array
	 * @throws ParameterException
	 * @throws \app\lib\exception\TokenException
	 */
	public function getAppToken($ac = '', $se = '')
	{
		(new AppTokenGet())->goCheck();

		$app = new AppToken();
		$token = $app->get($ac, $se);
		return [
			'token' => $token
		];
	}

	/**
	 * 获取登录
	 * @return array
	 * @throws ParameterException
	 * @throws \app\lib\exception\TokenException
	 */
	public function login()
	{
		$validate = new UserNew();
		$validate->goCheck();
		$type = Request::instance()->header('source');
		// 获取参数
		$dataArray = $validate->getDataByRule(input('post.'));

		$app = new AppToken();
		$result = $app->checkLogin($dataArray['username'], $dataArray['password']);
		return $result;
	}

	/**
	 * 注册用户
	 * @return TbMember
	 * @throws ParameterException
	 */
	public function register()
	{
		$validate = new UserNew();
		$validate->goCheck();
		$type = Request::instance()->header('source');
		// 获取参数
		$dataArray = $validate->getDataByRule(input('post.'));
		$dataArray['register_type'] = $type;
		$dataArray['id'] = create_guid('member');
//		Log::record($dataArray);
		return TbMember::create($dataArray);
	}
}