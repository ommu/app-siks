<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED | E_STRICT);
date_default_timezone_set('Asia/Jakarta');
ini_set('post_max_size', '8M');
ini_set('upload_max_filesize', '16M');

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../protected/vendor/autoload.php');
require(__DIR__ . '/../protected/vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../protected/config/bootstrap.php');
require(__DIR__ . '/app/config/bootstrap.php');

$config = \yii\helpers\ArrayHelper::merge(
	require(__DIR__ . '/../protected/config/main.php'),
	require(__DIR__ . '/app/config/main.php'),
	(is_readable(__DIR__ . '/../protected/config/dynamic.php'))?
		require(__DIR__ . '/../protected/config/dynamic.php'):
		[],
	(is_readable(__DIR__ . '/app/config/dynamic.php'))?
		require(__DIR__ . '/app/config/dynamic.php'):
		[]
);

if(\app\components\Application::isDev()) {
	$config = \yii\helpers\ArrayHelper::merge(
		require(__DIR__ . '/../protected/config/main.php'),
		require(__DIR__ . '/app/config/main.php'),
		(is_readable(__DIR__ . '/../protected/config/dynamic-dev.php'))? 
			require(__DIR__ . '/../protected/config/dynamic-dev.php'): 
			require(__DIR__ . '/../protected/config/dynamic.php'),
		(is_readable(__DIR__ . '/app/config/dynamic-dev.php'))? 
			require(__DIR__ . '/app/config/dynamic-dev.php'): 
			require(__DIR__ . '/app/config/dynamic.php')
	);

	// generate assets directory
	$assets = dirname(__FILE__).'/assets';
	if(!file_exists($assets))
		@mkdir($assets, 0777, true);
	else
		@chmod($assets, 0777, true);
	
	// generate cache directory
	$cache = dirname(__FILE__).'/cache';
	if(!file_exists($cache))
		@mkdir($cache, 0777, true);
	else
		@chmod($cache, 0777, true);
	
	// generate runtime directory in protected
	$runtime = dirname(__FILE__).'/app/runtime';
	if(!file_exists($runtime))
		@mkdir($runtime, 0777, true);
	else
		@chmod($runtime, 0777, true);
}

$app = new \app\components\Application($config);
$app->run();