<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-19
 * Time: 14:53
 */

return [
	'wechat_check' => true, // 标记是否为微信小程序审核
	'img_prefix' => 'https://www.edgarhao.cn/icestore/wecstore/public/images', // 图片前缀
	'token_expire_in' => 0,
	'img_prefix_qiniu' => 'http://qiniucloud.edgarhao.cn/', // 七牛云存储前缀
	'store_type' => ['key' => 'qiniu', 'type' => 3], // 存储类型 1。本地，2 网络，3 七牛，4 阿里云
];