<?php


namespace App\Orm\Manager;


use EasySwoole\ORM\AbstractModel;

class RoleOrm extends AbstractModel
{
	protected $tableName = "admin_roles";
	protected $autoTimeStamp = "datetime";

}
