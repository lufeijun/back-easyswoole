<?php


namespace App\Orm\Manager;


use EasySwoole\ORM\AbstractModel;

class UserOrm extends AbstractModel
{
	protected $tableName = 'admin_users';
	protected $autoTimeStamp = "datetime";
	protected $updateTime = 'update_at';
	protected $createTime = 'create_at';


	// 验证密码
	public function checkPwd( $pwd )
	{
		return md5($pwd) === $this->pwd;
	}

	// 生成新密码
	public function generatePwd( $pwd ) {
		return md5( $pwd );
	}

}
