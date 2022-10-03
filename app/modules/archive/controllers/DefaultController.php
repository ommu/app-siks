<?php
/**
 * DefaultController
 * @var $this app\components\View
 *
 * Default controller for the `archive` module
 * Reference start
 * TOC :
 *	Index
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 20 May 2019, 11:29 WIB
 * @link https://bitbucket.org/ommu/siks
 *
 */

namespace siks\app\modules\archive\controllers;

use app\components\Controller;
use mdm\admin\components\AccessControl;

class DefaultController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function allowAction(): array {
		return ['index'];
	}

	/**
	 * Renders the index view for the module
	 * @return string
	 */
	public function actionIndex()
	{
		$this->view->title = 'archives';
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('index');
	}
}
