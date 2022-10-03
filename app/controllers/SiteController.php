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
	public function getViewPath()
	{
		return Yii::getAlias('@siks/app/views') . DIRECTORY_SEPARATOR . $this->id;
	}
}
