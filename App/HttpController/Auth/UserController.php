<?php


namespace App\HttpController\Auth;


use App\HttpController\JwtController;
use App\Model\Privilege\ManagerModel;
use EasySwoole\Jwt\Jwt;

class UserController extends JwtController
{
	// 登录部分
	public function login()
	{

		$email = $this->input('email');
		$pwd = $this->input('pwd','123');


		$user = ManagerModel::getUserByEmail( $email );

		if ( ! $user ) {
		   return  $this->apiResult('用户名或密码错误');
		}


		if ( ! $user->enable ) {
			return  $this->apiResult('此账户已经关闭');
		}

		if ( $user->pwd != md5( $pwd ) ) {
			return  $this->apiResult('用户名或密码错误');
		}



		$instance = \EasySwoole\EasySwoole\Config::getInstance();
		// 获取配置 按层级用点号分隔
		$jwt =  $instance->getConf('JWT');
		$jwtObject = Jwt::getInstance()
			->setSecretKey( $jwt['secret_key'] ) // 秘钥
			->publish();

		$jwtObject->setAlg( $jwt['alg'] ); // 加密方式
		$jwtObject->setAud( $jwt['aud'] ); // 用户
		$jwtObject->setExp(time()+ $jwt['exp'] ); // 过期时间
		$jwtObject->setIat(time()); // 发布时间
		$jwtObject->setIss( $jwt['iss'] ); // 发行人
		$jwtObject->setJti(md5(time())); // jwt id 用于标识该jwt
		$jwtObject->setNbf(time()+60*5); // 在此之前不可用
		$jwtObject->setSub( $jwt['sub'] ); // 主题

        // 自定义数据
		$jwtObject->setData([
			'id' => $user->id,
			'name' => $user->name,
			'email' => $user->email,
		]);

		// 最终生成的token
		$token = $jwtObject->__toString();


		return $this->writeJson(0,['token'=>$token],'登录成功');
	}



	// 用户信息
	public function info()
	{
		$user = ManagerModel::getUserById( $this->managerId );

		return $this->writeJson(0,$user);
	}


	// 退出登录
	public function logout()
	{
		return $this->writeJson();
	}



	// 用户列表
	public function list()
	{
		$search = $this->input('search',[]);
		$all = ManagerModel::getUserList( $search );


		return $this->writeJson(0,$all);
	}

	// 用户更新
	public function update()
	{
		$isEdit = $this->input('is_edit',-1);
		$user = $this->input('user',[]);

		return $this->apiResult( ManagerModel::updateUser($user,$isEdit) );

	}

	// 更新头像
	public function avatar()
	{
		return $this->apiResult( ManagerModel::updateUserAvatar( $this->managerId , $this->input('avatar') ) );
	}

	// 更新密码
	public  function pwd() {
		return $this->apiResult( ManagerModel::updatePwd($this->managerId , $this->input('pwd',[])) );
	}


}
