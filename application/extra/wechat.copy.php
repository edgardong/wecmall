<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-22
 * Time: 13:36
 */

return [
  'app_id' => 'wx9ba5117f7cbce76d',
  'app_secret' => '29a7809bc04fac72d8af77033eb8fafd',
  'login_url' => 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code',
  // 微信获取access_token的url地址
  'access_token_url' => "https://api.weixin.qq.com/cgi-bin/token?" .
    "grant_type=client_credential&appid=%s&secret=%s",
];