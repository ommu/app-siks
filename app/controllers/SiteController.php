<?php
/**
 * SiteController
 * @var $this app\components\View
 *
 * Reference start
 * TOC :
 *	Update
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 10 May 2019, 07:57 WIB
 * @link https://bitbucket.org/ommu/siks
 *
 */

namespace siks\app\controllers;

use Yii;

class SiteController extends \app\controllers\SiteController
{
	/**
	 * {@inheritdoc}
	 */
	public function actions()
	{
		$actions = parent::actions();

		if (class_exists('siks\app\actions\ContactAction')) {
			$actions = ArrayHelper::merge($actions, [
				'contact' => [
					'class' => 'siks\app\actions\ContactAction',
					'view' => 'front_contact',
				],
			]);
		}

		if(Yii::$app->isMaintenance()) {
			$maintenance_theme = Yii::$app->setting->get(join('_', [$appName, 'maintenance_theme']), 'arnica');
			$ContactActionClass = strtr('themes\{theme}\actions\ContactAction', [
				'{theme}' => $maintenance_theme,
			]);
			if (class_exists($ContactActionClass)) {
				$actions = ArrayHelper::merge($actions, [
					'contact' => [
						'class' => $ContactActionClass,
						'view' => 'front_contact',
					],
				]);
			}
		}

		return $actions;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getViewPath()
	{
		return Yii::getAlias('@siks/app/views') . DIRECTORY_SEPARATOR . $this->id;
	}
}
