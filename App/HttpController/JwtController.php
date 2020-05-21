<?php


namespace App\HttpController;


use EasySwoole\Jwt\Jwt;

class JwtController extends BaseController
{
	public $managerId = 0; // 每次需要重新赋值

	// 不需要登录验证的路由
	private $whiteList = [
		'/auth/user/login',
		'/auth/user/logout',
	];

	// 登录验证
	public function onRequest(?string $action): ?bool
	{
		if (parent::onRequest($action)) {
			//白名单判断
			if ( in_array($this->request()->getServerParams()['request_uri'], $this->whiteList)) {
				return true;
			}
			//获取登入信息
			$tokenMsg = $this->checkToken();
			if ( $tokenMsg ) {
				$this->writeJson(50014, '', $tokenMsg);
				return false;
			}
			return true;
		}

		$this->writeJson(0, '', '请进行登录');
		return false;

	}


	// 检测 api 请求待的 token
	private function checkToken()
	{
		$tokens = $this->request()->getHeader('token');

	    if ( empty( $tokens ) ) {
	   	   return 'token 字段缺失';
	    }


		try {
			$jwt =  \EasySwoole\EasySwoole\Config::getInstance()->getConf('JWT');
			$jwtObject = Jwt::getInstance()->setSecretKey( $jwt['secret_key'] )->decode($tokens[0]);
			$status = $jwtObject->getStatus();

			switch ($status)
			{
				case  1:
//					echo '验证通过';
//					$jwtObject->getAlg();
//					$jwtObject->getAud();
//					$jwtObject->getData();
//					$jwtObject->getExp();
//					$jwtObject->getIat();
//					$jwtObject->getIss();
//					$jwtObject->getNbf();
//					$jwtObject->getJti();
//					$jwtObject->getSub();
//					$jwtObject->getSignature();
//					$jwtObject->getProperty('alg');
					$this->managerId = $jwtObject->getData()['id'];
					return '';
					break;
				case  -1:
					return 'token 无效';
					break;
				case  -2:
					return '登录过期，请重新登录';
					break;
			}
		} catch (\EasySwoole\Jwt\Exception $e) {
			return  '';
		}

		return '';
	}


}
