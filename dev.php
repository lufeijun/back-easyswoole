<?php
return [
    'SERVER_NAME' => "EasySwoole",
    'MAIN_SERVER' => [
        'LISTEN_ADDRESS' => '0.0.0.0',
        'PORT' => 9501,
        'SERVER_TYPE' => EASYSWOOLE_WEB_SERVER, //可选为 EASYSWOOLE_SERVER  EASYSWOOLE_WEB_SERVER EASYSWOOLE_WEB_SOCKET_SERVER,EASYSWOOLE_REDIS_SERVER
        'SOCK_TYPE' => SWOOLE_TCP,
        'RUN_MODEL' => SWOOLE_PROCESS,
        'SETTING' => [
            'worker_num' => 8,
            'reload_async' => true,
            'max_wait_time'=>3
        ],
        'TASK'=>[
            'workerNum'=>4,
            'maxRunningNum'=>128,
            'timeout'=>15
        ]
    ],
    'TEMP_DIR' => null,
    'LOG_DIR' => null,

	'URL' => 'http://back.vueelement.com/',

	'MYSQL'  => [
		'host'          => '127.0.0.1',
		'port'          => 3306,
		'user'          => 'easyswoole',
		'password'      => '123456',
		'database'      => 'easyswoole',
		'timeout'       => 5,
		'charset'       => 'utf8mb4',
	],


	'JWT' => [
		'secret_key' => 'easyswoole', // 密匙
		'alg' => 'HMACSHA256',  // 加密方式
		'aud' => 'HMACSHA256',  // 用户
		'exp' => 3600,  // 过期时间
		'iss' => 'lufeijun1234',  // 发行人
		'sub' => '登录管理',  // 主题
	],

];
