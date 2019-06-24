<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-03-20
 * Time: 18:17
 */

namespace app\api\service;


class WxMessage
{
	private $sendUrl = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?";
	private $touser;
	//不让子类控制颜色
	private $color = 'black';

	protected $tplID;
	protected $page;
	protected $formID;
	protected $data;
	protected $emphasisKeyWord;

	function __construct()
	{
		$accessToken = new AccessToken();
		$token = $accessToken->get();
		$this->sendUrl = sprintf($this->sendUrl .
			"access_token=%s", $token);
	}

	// 开发工具中拉起的微信支付prepay_id是无效的，需要在真机上拉起支付

	/**
	 * 发送微信消息
	 * @param $openID
	 * @return bool
	 */
	protected function sendMessage($openID)
	{

		$data = [
			'touser' => $openID,
			'template_id' => $this->tplID,
			'page' => $this->page,
			'form_id' => $this->formID,
			'data' => $this->data,
//            'color' => $this->color,
			'emphasis_keyword' => $this->emphasisKeyWord
		];
		$result = curl_post($this->sendUrl, $data);
		$result = json_decode($result, true);
		if ($result['errcode'] == 0) {
			return true;
		} else {
			throw new Exception('模板消息发送失败,  ' . $result['errmsg']);
		}
	}
}