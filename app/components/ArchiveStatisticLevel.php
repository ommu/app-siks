<?php
/**
 * ArchivePopular
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 17 July 2019, 23:21 WIB
 * @link https://bitbucket.org/ommu/bpadjogja-portal
 * 
 */

namespace siks\app\components;

use Yii;
use yii\helpers\Url;
use ommu\archive\models\ArchiveLevel;
use ommu\archive\models\Archives;
use yii\helpers\Inflector;

class ArchiveStatisticLevel extends \yii\base\Widget
{
	/**
	 * {@inheritdoc}
	 */
	public $levelId;

	/**
	 * {@inheritdoc}
	 */
	public $materialIcons;

	/**
	 * {@inheritdoc}
	 */
	public $levelName;

	/**
	 * {@inheritdoc}
	 */
	public $count;

	/**
	 * {@inheritdoc}
	 */
	public $url;

	public function init()
	{
		parent::init();

		if(!$this->materialIcons)
			$this->materialIcons = 'assessment';
	}

	public function run()
	{
		if($this->levelId) {
			$model = ArchiveLevel::find()
				->select(['id', 'level_name'])
				->andWhere(['id'=>$this->levelId])
				->andWhere(['publish' => 1])
				->one();

			$this->levelName = Yii::t('app', Inflector::pluralize($model->level_name_i));
			$this->count = (int)$model->getArchives(true, 1);
			$this->url = Url::to(['/archive/site/index']);
			if(strtolower($model->level_name_i) != 'fond')
                $this->url = Url::to(['/archive/site/index', 'level'=>$model->id]);

		} else {
			$model = Archives::find()
				->where(['publish' => 1])
				->count();

			$this->levelName = Yii::t('app', Inflector::pluralize('All'));
			$this->count = (int)$model;
			$this->url = Url::to(['/archive/site/index', 'level'=>'all']);
		}

		return $this->render('archive_statistic_level', [
			'model' => $model,
			'icon' => $this->materialIcons,
		]);
	}
}