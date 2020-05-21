<?php
namespace EasySwoole\EasySwoole;


use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Message\Status;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\ORM\Db\Connection;
use EasySwoole\ORM\DbManager;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');
    }

    public static function mainServerCreate(EventRegister $register)
    {
		// TODO: Implement mainServerCreate() method.
		$config = new \EasySwoole\ORM\Db\Config(Config::getInstance()->getConf('MYSQL'));
		DbManager::getInstance()->addConnection(new Connection($config));

		// 记录日志
		DbManager::getInstance()->onQuery(function ($res, $builder, $start) {
			// 打印参数 OR 写入日志
			$msg = $builder->getLastQuery() . ' | '  . bcsub(microtime(true), $start, 5);
			Logger::getInstance()->info( $msg );
		});

    }

    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.

		// 跨域
		$response->withHeader('Access-Control-Allow-Origin', '*');
		$response->withHeader('Access-Control-Allow-Methods', 'GET, POST');
		$response->withHeader('Access-Control-Allow-Credentials', 'true');
		$response->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With,token');
		if ($request->getMethod() === 'OPTIONS') {
			$response->withStatus(Status::CODE_OK);
			return false;
		}
		return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}
