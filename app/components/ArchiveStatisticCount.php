<?php
namespace siks\app\components;

use ommu\archive\models\ArchiveLevel;

class ArchiveStatisticCount extends \yii\base\Widget
{
	/**
	 * {@inheritdoc}
	 */
	public $levelId;

	/**
	 * {@inheritdoc}
	 */
	public $materialIcons = 'assessment';

	public function run()
	{
		$model = ArchiveLevel::find()
			->select(['id', 'level_name'])
			->where(['id'=>$this->levelId])
			->one();

		return $this->render('archive_statistic_count', [
			'model' => $model,
			'icon' => $this->materialIcons,
		]);
	}
}