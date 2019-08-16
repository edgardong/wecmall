<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-05-10
 * Time: 17:51
 */

namespace app\api\controller\common\v1;

use app\api\service\Common as CommonService;
use think\Log;

class Common
{
	/**
	 * 上传文件
	 * @return array
	 * @throws \Exception
	 */
	public function uploadFile()
	{
		$file = request()->file('file');
		$type = input('post.type');
		$service = new CommonService();
		$result = $service->upload($file, $type);

		return $result;
	}

	/**
	 * 记录日志
	 */
	public function uploadLog(){
		$content= input('post.');
		Log::record($content);
		return 'log success';
	}
}