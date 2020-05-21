<?php


namespace App\HttpController;


use EasySwoole\Http\AbstractInterface\AbstractRouter;
use FastRoute\RouteCollector;


/**
 * 定义路由的地方
 * Class Router
 * @package App\HttpController
 */
class Router extends AbstractRouter
{

	function initialize(RouteCollector $routeCollector)
	{
		$routeCollector->addRoute(['GET','POST'], '/', 'HomeController/index');
		$routeCollector->addRoute(['POST'], '/upload/image', 'Tool/FilesController/image');


		// 用户部分
		$routeCollector->addGroup('/auth',function (RouteCollector $collector) {

			// 用户部分
			$collector->addGroup('/user',function (RouteCollector $collector){
				$collector->addRoute('POST', '/login', 'Auth/UserController/login');
				$collector->addRoute('POST', '/info', 'Auth/UserController/info');
				$collector->addRoute('POST', '/logout', 'Auth/UserController/logout');

				$collector->addRoute('POST', '/list', 'Auth/UserController/list');
				$collector->addRoute('POST', '/update', 'Auth/UserController/update');
				$collector->addRoute('POST', '/avatar', 'Auth/UserController/avatar');
				$collector->addRoute('POST', '/pwd/change', 'Auth/UserController/pwd');
			});


			// 角色列表
			$collector->addGroup('/role',function (RouteCollector $collector){
				$collector->addRoute('POST', '/list', 'Auth/RoleController/list');
				$collector->addRoute('POST', '/update', 'Auth/RoleController/update');
				$collector->addRoute('POST', '/permission/get', 'Auth/RoleController/permissionGet');
				$collector->addRoute('POST', '/permission/update', 'Auth/RoleController/permissionUpdate');
			});




		});



	}
}
