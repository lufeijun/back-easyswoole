<?php

namespace App\HttpController;

use EasySwoole\EasySwoole\Logger;
use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Http\Message\Status;
use EasySwoole\Http\Response;


/**
 * 作为控制器的类的 业务层的基类
 *
 * Class BaseController
 * @package App\HttpController
 */

class BaseController extends Controller
{

	// 重写一下
	protected function writeJson($statusCode = 0, $result = null, $msg = 'success')
	{
		if (!$this->response()->isEndResponse()) {
			if ( is_null( $result ) ) {
				$result = new \StdClass();
			}
			$data = Array(
				"code" => $statusCode,
				"data" => $result,
				"msg" => $msg
			);
			$this->response()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			$this->response()->withHeader('Content-type', 'application/json;charset=utf-8');
			// $this->response()->withStatus($statusCode);
			return true;
		} else {
			return false;
		}
	}

	protected function apiResult( string $result)
	{
		if ( $result ) {
			return $this->writeJson(1, null ,$result);
		} else {
			return $this->writeJson();
		}
	}


	// 发生异常
	public function onException(\Throwable $throwable): void
	{
		// 发送邮件

		// 记录到日志
		Logger::getInstance()->info( $throwable->getMessage() );

		// 返回给客户端的 json
		$this->writeJson(Status::CODE_INTERNAL_SERVER_ERROR,null,'系统错误，请联系技术');
	}


	public function input( String $key , $default = '' )
	{
		$result = $this->request()->getRequestParam($key);

		if ( $result ) {
			return $result;
		}

		$arr = json_decode($this->request()->getSwooleRequest()->rawContent(),true);

		if ( isset( $arr[$key] ) ) {
			return $arr[$key];
		}


		return $default;
	}



}
