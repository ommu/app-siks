<?php
namespace siks\app\assets;

class AciTreeAsset extends \yii\web\AssetBundle
{
	public $sourcePath = '@siks/source';
	
	public $css = [
		'css/custom.css',
	];

	public $js = [
		'js/acitree.js',
	];

	public $depends = [
		'ommu\archive\assets\AciTreePluginAsset',
	];

	public $publishOptions = [
		'forceCopy' => YII_DEBUG ? true : false,
	];
}