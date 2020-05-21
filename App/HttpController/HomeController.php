<?php


namespace App\HttpController;


class HomeController extends BaseController
{

	public function index()
	{
		return $this->writeJson(0,[],'欢迎使用后台接口');
	}

}
