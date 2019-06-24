<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-22
 * Time: 13:28
 */

namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\enum\UserTypeEnum;
use app\lib\exception\TokenException;
use app\lib\exception\WeChatException;
use think\Exception;

use app\api\model\User as UserModel;
use think\Loader;

//Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');
Loader::import('Alipay.AopSdk', EXTEND_PATH, '.php');

class UserToken extends Token
{

	protected $code;
	protected $wxAppId;
	protected $wxAppSecret;
	protected $wxLoginUrl;
	protected $type;

	/**
	 * UserToken constructor.
	 * @param $code
	 */
	function __construct($code, $type = "wechat")
	{
		$this->type = $type;
		$this->code = $code;
		$this->wxAppId = config('wechat.app_id');
		$this->wxAppSecret = config('wechat.app_secret');
		$this->wxLoginUrl = sprintf(config('wechat.login_url'),
			$this->wxAppId, $this->wxAppSecret, $this->code);
	}

	/**
	 * 获取openid
	 * @return mixed
	 * @throws
	 */
	public function get()
	{

		if ($this->type == 'wechat') {
			$result = curl_get($this->wxLoginUrl);
			$wxResult = json_decode($result, true);
			if (empty($wxResult)) {
				throw new Exception('微信内部错误，获取openid时异常');
			} else {
				$loginFail = array_key_exists('errcode', $wxResult);
				if ($loginFail || $loginFail['errcode'] != 0) {
					// 处理登录日志
					$this->processLoginError($wxResult);
				} else {
					// 颁发令牌
					return $this->grantToken($wxResult);
				}
			}
		} else {
			$c = new \AopClient();
			$c->format = "json";
			$c->charset = "GBK";
			$c->signType = "RSA2";
			$c->appId = config('alipay.app_id');
			$c->rsaPrivateKey = config('alipay.rsa_private_key');
			$c->alipayrsaPublicKey = config('alipay.rsa_public_key');
			$request = new \AlipaySystemOauthTokenRequest();
			$request->setGrantType('authorization_code');
			$request->setCode($this->code);
			$result = $c->execute($request);

			$responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
//      $resultCode = $result->$responseNode->code;
//      access_token:"authusrB89be7a7bad234b5a980ec0fa301a4X20"
//      alipay_user_id:"20880067491168601130241352012020"
//      expires_in:1296000
//      re_expires_in:2592000
//      refresh_token:"authusrB835d0375285246a8b6031ee46c081X20"
//      user_id:"2088802143048200"
			$wxResult = [
				'openid' => $result->$responseNode->user_id
			];
			return $this->grantToken($wxResult, 'alipay');
		}
	}

	/**
	 * 处理登录异常
	 * @param $wxResult
	 * @throws WeChatException
	 */
	private function processLoginError($wxResult)
	{
		throw new WeChatException([
			'msg' => $wxResult['errmsg'],
			'errorCode' => $wxResult['errcode']
		]);
	}


	private function grantToken($wxResult, $type = 'wechat')
	{
		// 获取openid
		// 从数据库中查看，用户是否存在，存在不处理，不存在，新增记录
		// 生成令牌
		// 设置缓存
		// 返回token
		$openId = $wxResult['openid'];
//    $session_key = $wxResult['session_key'];
//    $unionid = $wxResult['unionid'];

		$user = UserModel::getByOpenId($openId);

		if ($user) {
			$uid = $user->id;
		} else {
			$uid = $this->newUser($openId, $type);
		}
		// 预备缓存数据
		$cachedValue = $this->prepareCachedValue($wxResult, $uid);
		// 保存到缓存中
		$token = $this->saveToCache($cachedValue);

		return $token;
	}

	/**
	 * 保存到缓存中
	 * @param $cachedValue
	 * @return string
	 * @throws TokenException
	 */
	private function saveToCache($cachedValue)
	{
		$key = self::generateToken();
		$value = json_encode($cachedValue);
		// 缓存有效期
		$expire_in = config('setting.token_expire_in');

		$result = cache($key, $value, $expire_in);

		if (!$result) {
			throw new TokenException([
				'msg' => '服务器缓存异常',
				'errorCode' => 10005
			]);
		}

		return $key;

	}

	/**
	 * 准备缓存数据
	 * @param $wxResult
	 * @param $uid
	 * @return mixed
	 */
	private function prepareCachedValue($wxResult, $uid)
	{
		$cachedValue = $wxResult;
		$cachedValue['uid'] = $uid;
		// scope: 16 app 用户权限数值
		$cachedValue['scope'] = ScopeEnum::User;
		$cachedValue['type'] = UserTypeEnum::User;
		// scope: 32 cms 管理员 用户权限数值
//    $cachedValue['scope'] = ScopeEnum::Super;

		return $cachedValue;
	}

	/**
	 * 创建新用户
	 * @param $openId
	 * @param $type
	 * @return mixed
	 */
	private function newUser($openId, $type)
	{
		$user = UserModel::create([
			'openid' => $openId,
			'user_type' => $type,
			'id' => create_guid('wechat')
		]);

		return $user->id;
	}
}