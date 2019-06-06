<?php
namespace siks\app\components;

use ommu\archive\models\Archives;

class ArchivePopular extends \yii\base\Widget
{
	/**
	 * {@inheritdoc}
	 */
	public $isNewest = false;

	/**
	 * {@inheritdoc}
	 */
	public $limit = 5;

	/**
	 * {@inheritdoc}
	 */
	public $ignoreLevel = [];

	public function run()
	{
		$setting = \ommu\archive\models\ArchiveSetting::find()
			->select(['maintenance_mode', 'reference_code_sikn', 'reference_code_separator'])
			->where(['id' => 1])
			->one();

		$model = Archives::find()
			->select(['id', 'parent_id', 'level_id', 'title', 'code'])
			->where(['IN', 'publish', [0,1]])
			->limit($this->limit);
		if(!empty($this->ignoreLevel))
			$model->andWhere(['NOT IN', 'level_id', $this->ignoreLevel]);
		if($this->isNewest == true)
			$model->orderBy('creation_date DESC, id DESC');
		else
			$model->orderBy('creation_date DESC');
		$model = $model->all();

		return $this->render('archive_popular', [
			'model' => $model,
			'setting' => $setting,
		]);
	}
}