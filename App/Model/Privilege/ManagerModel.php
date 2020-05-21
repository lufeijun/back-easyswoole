<?php

namespace App\Model\Privilege;


use App\Orm\Manager\AblityOrm;
use App\Orm\Manager\RoleOrm;
use App\Orm\Manager\UserOrm;
use EasySwoole\ORM\DbManager;

class ManagerModel
{


	public static function getUserList( array $search)
	{
		$limit = 30; // 每页个数
		$page = isset( $search['page'] )? $search['page'] : 1;

		$model = UserOrm::create();

		// 状态
		if ( $search['enable'] != '全部' ) {
			$enable = $search['enable'] == '在职' ? 1 : 0;
			$model = $model->where('enable',$enable);
		}

		// 角色
		if ( $search['role_name'] != '全部' ) {
			$role = RoleOrm::create()->where('name',$search['role_name'])->get();
			if ( $role ) {
				$model = $model->where('role_ids', '%,'.$role->id.',%','like');
			}
		}

		// 关键词
		if ( $search['keyword'] ) {

			$keyword = '%'. $search['keyword'] .'%';
			$query = sprintf("( `name` like '%s' or email like '%s'  )",
				addslashes($keyword),
				addslashes($keyword));

			// 有注入风险  %' or 1 like '%
			// $model = $model->where('( `name` like \'%'. $search['keyword'] .'%\' or email like \'%'. $search['keyword'] .'%\'  )');

			//
			$model = $model->where($query);

		}


		$model = $model->order('id','desc')->limit($limit * ($page - 1), $limit)->withTotalCount();

		// 列表数据
		$list = $model->all(null);

		$allRoles = RoleOrm::create()->indexBy('id');

		foreach ( $list as &$user ) {
			$roles = [];
			foreach ( explode(',',$user->role_ids) as $rId ) {
				if ( isset( $allRoles[$rId] ) ) {
					$roles[] = $allRoles[$rId]['name'];
				}
			}
			$user->enable = $user->enable?'在职':'离职';
			$user->roles = $roles;
		}
		unset($user);


		$total = $model->lastQueryResult()->getTotalCount();


		return  [
			'roles' => $allRoles,
			'list' =>  $list,
			'pages' => [
				'total' => $total,
				'per_page' => $limit,
				'current_page' => $page,
				'last_page' => ceil( $total / $limit ),
				'all_page' => ceil( $total / $limit ),
			],
		];

	}

	// 用户更新
	public static function updateUser( $user , $isEdit )
	{

		if ( UserOrm::create()->where('email',$user['email'])->where('id',$user['id'],'!=')->count() ) {
			return '用户邮箱不能重复';
		}

		$allRoles = RoleOrm::create()->indexBy('name');

		$roleIds = [];
		foreach ( $user['roles'] as $rName ) {
			if ( isset( $allRoles[$rName] ) ) {
				$roleIds[] = $allRoles[$rName]['id'];
			}
		}
		$roleIds = ',' . implode(',', $roleIds) . ',';
		$enable = $user['enable'] == '在职' ? 1 : 0;
		$avatar = '0.jpg';

		if ( $isEdit ) {
			// 编辑
			$obj = UserOrm::create()->get($user['id']);
			$obj->name = $user['name'];
			$obj->avatar = $avatar;
			$obj->enable = $enable;
			$obj->role_ids = $roleIds;
			if ( $user['pwd'] ) {
				$obj->pwd = $obj->generatePwd(  $user['pwd'] );
			}
			$obj->update();

		} else {
			// 新增
			$obj = UserOrm::create();
			$obj->name = $user['name'];
			$obj->email = $user['email'];
			$obj->avatar = $avatar;
			$obj->role_ids = $roleIds;
			$obj->enable = $enable;
			if ( ! $user['pwd'] ) {
				$user['pwd'] = '123456';
			}
			$obj->pwd = $obj->generatePwd(  $user['pwd'] );;
			$obj->save();
		}

		return '';

	}



	/**
	 * 通过 email 获取用户
	 */
	public static function getUserByEmail( String $email )
	{
		return UserOrm::create()->where('email',$email)->get();
	}

	/**
	 * 通过 id 获取用户
	 */
	public static function getUserById( int $id )
	{
		$user = UserOrm::create()->get($id);

		$user->role_ids = substr($user->role_ids,1,-1);
		// 权限
		$user->ability = self::getPermissionByRoleid( explode(',',$user->role_ids), true );

		$user->roles = RoleOrm::create()->where('id',explode(',',$user->role_ids),'in')->column('name');

		// 头像
		$user->avatar = \EasySwoole\EasySwoole\Config::getInstance()->getConf('URL') . 'images/avatar/' . $user->avatar;


		return $user;
	}

	// 更新头像
	public static function updateUserAvatar( $id , $avatar )
	{
		$user = UserOrm::create()->get($id);
		$avatar = explode('/',$avatar);
		$user->avatar = array_pop($avatar);
		$user->update();

		return '';

	}


	// 更新密码
	public static function updatePwd( $id , $pwdArr )
	{
		$user = UserOrm::create()->get($id);

		if ( ! isset( $pwdArr['old'] ) || ! isset( $pwdArr['new'] ) ) {
			return '参数错误';
		}

		// 验证密码
		if ( ! self::checkPwd($user,$pwdArr['old']) ) {
			return '旧密码错误';
		}

		// 更新密码
		$user->pwd = $user->generatePwd( $pwdArr['new'] );
		$user->update();

		return '';
	}


	// 验证密码
	public static function checkPwd( UserOrm $user , $pwd )
	{
		return $user->checkPwd($pwd);
	}



	public static function getAllRoles( string $order = 'desc'  )
	{
		return RoleOrm::create()->order('id',$order)->all();
	}


	// 角色
	public static function updateRole($id,$name)
	{
		if ( RoleOrm::create()->where('id',$id,'!=')->where('name',$name)->count() ) {
			return '角色名称不能重复';
		}

		if ( $id == 0 ) {
			$obj = RoleOrm::create();
			$obj->name = $name;
			$obj->save();
		} else {
			$obj = RoleOrm::create()->get($id);
			$obj->name = $name;
			$obj->update();
		}



		return '';

	}


	public static function getPermissionByRoleid( $roleIds , $inclueHalf = false )
	{
		$action = [];
		$menu = [];

		$roleIds = ( array )$roleIds;

		$all = AblityOrm::create()->where('role_id',$roleIds,'in');

		if ( ! $inclueHalf )
		{
			$all->where('half_checked',0);
		}

		foreach ( $all->all() as $obj ) {
			if ( $obj->type == 1 ) {
				$menu[] = $obj->ability;
			} elseif ( $obj->type == 2 ) {
				$action[] = $obj->ability;
			}
		}

		return ['action'=>$action,'menu'=>$menu];
	}

	// 更新角色对应的权限
	public static function updatePermission( $roleId , $menu = [] , $menuHalfArr = [] , $action = [] )
	{
		$result = '';
		try{
			//开启事务
			DbManager::getInstance()->startTransaction();

			AblityOrm::create()->destroy([
				'role_id' => $roleId,
			]);
			$inserts = [];

			foreach ( $action as $a ) {
				$inserts[] = [
					'role_id' => $roleId,
					'type' => 2,
					'ability' => $a,
					'half_checked' => 0,
				];
			}
			foreach ( $menu as $m ) {
				$inserts[] = [
					'role_id' => $roleId,
					'type' => 1,
					'ability' => $m,
					'half_checked' => 0,
				];
			}

			foreach ( $menuHalfArr as $m ) {
				$inserts[] = [
					'role_id' => $roleId,
					'type' => 1,
					'ability' => $m,
					'half_checked' => 1,
				];
			}
			AblityOrm::create()->saveAll($inserts);


			DbManager::getInstance()->commit();
		} catch(\Throwable  $e){
			$result = $e->getMessage();
			//回滚事务
			DbManager::getInstance()->rollback();
		}


		return $result;

	}


}
