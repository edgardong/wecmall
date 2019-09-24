<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-22
 * Time: 13:36
 */

return [
  'app_id' => 'APPID', //
  'rsa_private_key' => '你的密钥',
  'rsa_public_key' => '你的公钥',
  'app_secret' => '应用密钥',
  'login_url' => 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code',
  // 微信获取access_token的url地址
  'access_token_url' => "https://api.weixin.qq.com/cgi-bin/token?" .
    "grant_type=client_credential&appid=%s&secret=%s",
	'notify_url'=>'http://store.free.idcfengye.com/wecstore/public/index.php/api/v1/alipay/notify'
];