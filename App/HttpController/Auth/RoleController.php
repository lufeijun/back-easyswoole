<?php


namespace App\HttpController\Auth;


use App\HttpController\JwtController;
use App\Model\Privilege\ManagerModel;

class RoleController extends JwtController
{

	private $ablity = [];

	public function list()
	{
		$all = ManagerModel::getAllRoles();

		return $this->writeJson(0,$all);
	}


	public function update()
	{
		$id = $this->input('id',0);
		$name = $this->input('name','');

		return	$this->apiResult( ManagerModel::updateRole($id,$name) );

	}


	public function permissionGet()
	{
		$roleId = $this->input('id');

		$ablity = $this->getAblity();

		$action = $this->_dealForTree( $ablity['actionTree'] );
		$menu = $this->_dealForTree( $ablity['menuTree'] );

		return $this->writeJson(0,['menu'=>$menu,'action'=>$action,'checked'=>ManagerModel::getPermissionByRoleid($roleId)]);
	}


	public function permissionUpdate()
	{
		$roleId = $this->input('id');
		$menuArr = $this->input('menu',[]);
		$menuHalfArr = $this->input('menu_half',[]);
		$actionArr = $this->input('action',[]);


		return $this->apiResult( ManagerModel::updatePermission( $roleId , $menuArr , $menuHalfArr , $actionArr  ) );
	}



	public function getAblity()
	{
		if ( empty( $this->ablity ) ) {
			$this->ablity = require_once 'config/Ablity.php';
		}
		return $this->ablity;
	}

	private function _dealForTree( array $data )
	{
		$result = [];
		$have = []; // 标志数组，防止重复
		foreach ( $data as $menu )
		{
			$tempStr = '';
			foreach ( explode(',',$menu) as $m )
			{
				$key = $tempStr . $m . ',';
				if ( ! in_array( $key , $have ) )
				{
					$result[] = [
						'label' => $m,
						'parent' => $tempStr,
						'key' =>  $key,
					];
					$have[] = $key;
				}
				$tempStr .= $m.',';
			}
		}

		$data = array_column($result, null, 'key');
		// 树形结构的开始
		$tree = [];
		foreach ($data as $key => $val) {
			if ($val['parent'] == '') {
				$tree[] = &$data[$key];
			} else {
				$data[$val['parent']]['children'][] = &$data[$key];
			}
		}

		return $tree;
	}



}
