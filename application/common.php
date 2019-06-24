<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * @params $url get请求的地址
 * @params int $httpCode 返回状态码
 * @throws
 * @return mixed
 */
function curl_get($url, &$httpCode = 0)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	// 不做证书校验，Linux环境时请修改为true
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

	$file_contents = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	return $file_contents;
}

/**
 * @param string $url post请求地址
 * @param array $params
 * @return mixed
 */
function curl_post($url, array $params = array())
{
	$data_string = json_encode($params);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt(
		$ch, CURLOPT_HTTPHEADER,
		array(
			'Content-Type: application/json'
		)
	);
	$data = curl_exec($ch);
	curl_close($ch);
	return ($data);
}

/**
 * Post 请求
 * @param $url
 * @param $rawData
 * @return bool|string
 */
function curl_post_raw($url, $rawData)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $rawData);
	curl_setopt(
		$ch, CURLOPT_HTTPHEADER,
		array(
			'Content-Type: text'
		)
	);
	$data = curl_exec($ch);
	curl_close($ch);
	return ($data);
}

/**
 * 获取指定长度的随机字符串
 * @param $length 字符串长度
 * @return string
 */
function getRandChars($length)
{
	$str = '';
	$strPol = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
	$max = strlen($strPol) - 1;
	for ($i = 0; $i < $length; $i++) {
		$str .= $strPol[rand(0, $max)];
	}
	return $str;
}


/**
 * 生成唯一的GUID
 * @param string $namespace
 * @return string
 */
function create_guid($namespace = '')
{
	static $guid = '';
	$uid = uniqid("", true);
	$data = $namespace;
	$data .= $_SERVER['REQUEST_TIME'];
	$data .= $_SERVER['HTTP_USER_AGENT'];
	$data .= $_SERVER['REMOTE_ADDR'];
	$data .= $_SERVER['REMOTE_PORT'];
	$hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
	$guid =
		substr($hash, 0, 8) .
		'-' .
		substr($hash, 8, 4) .
		'-' .
		substr($hash, 12, 4) .
		'-' .
		substr($hash, 16, 4) .
		'-' .
		substr($hash, 20, 12);
	return $guid;
}


