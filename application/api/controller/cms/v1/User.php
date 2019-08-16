<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-08-12
 * Time: 14:07
 */

namespace app\api\controller\cms\v1;


use app\api\controller\BaseController;

class User extends BaseController
{
	public function getApiName()
	{
		return "hello, cms api v1";
	}

	public function getApiName2($name)
	{
		return $this->getApiName() . $name;
	}
}