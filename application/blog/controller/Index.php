<?php

namespace app\blog\controller;

class Index
{
	public function index()
	{
		return ['id' => 'hello edgarhao.blog module', 'path' => $_SERVER];
	}
}
