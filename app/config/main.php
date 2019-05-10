<?php
$params = \yii\helpers\ArrayHelper::merge(
	(\app\components\Application::isDev() && (is_readable(__DIR__ . '/../../../protected/config/params-dev.php')))?
		require(__DIR__ . '/../../../protected/config/params-dev.php'):
		require(__DIR__ . '/../../../protected/config/params.php'),
	(\app\components\Application::isDev() && (is_readable(__DIR__ . '/params-dev.php')))?
		require(__DIR__ . '/params-dev.php'):
		require(__DIR__ . '/params.php')
);
$bn = \app\components\Application::getAppId();

$config = [
	'name' => 'Sistem Informasi Kearsipan Statis',
	'id' => 'basic',
	'runtimePath' => dirname(__DIR__) . '/runtime',
	'controllerNamespace' => 'siks\app\controllers',
	'bootstrap' => [],
	'components' => [
		'request' => [
			// !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
			'cookieValidationKey' => '7f1904f4-c6cb-40d3-9b03-9195a82baa78'
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		'session' => [
			'class' => 'yii\web\Session',
			'name' => $bn,
			'cookieParams' => ['lifetime' => 7 * 24 * 60 * 60],
			'timeout' => 7 * 24 * 3600,
			'useCookies' => true,
		],
		'jwt' => [
			'class'    => 'app\components\Jwt',
			'key'      => 'Z3jzZOd80qMhyVZnZQLeQnB8M1DMQB5G',
			'issuer'   => 'http://dpad.jogjaprov.go.id/siks',
			'audiance' => 'http://dpad.jogjaprov.go.id/siks',
			'id'       => '7f1904f4-c6cb-40d3-9b03-9195a82baa78',
		],
		'authManager' => [
			'class'             => 'mdm\admin\components\DbManager',
			'assignmentTable'   => 'ommu_core_auth_assignment',
			'itemTable'         => 'siks_auth_item',
			'itemChildTable'    => 'siks_auth_item_child',
			'ruleTable'         => 'siks_auth_rule',
		],
	],
	'params' => $params,
	'modules' => [
		'admin' => [
			'class' => 'app\modules\admin\Module',
		],
		'rbac' => [
			'class' => 'app\modules\rbac\Module',
			'controllerMap' => [
				'menu' => [
					'class' => 'app\modules\rbac\controllers\MenuController',
				],
			],
		],
		'user' => [
			'class' => 'app\modules\user\Module',
		],
	],
];

return $config;
