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
		$model = Archives::find()
			->select(['id', 'level_id', 'title', 'code'])
			->where(['publish'=>0])
			->limit($this->limit);
		if(!empty($this->ignoreLevel))
			$model->andWhere(['NOT IN', 'level_id', $this->ignoreLevel]);
		if($this->isNewest == true)
			$model->orderBy('creation_date DESC');
		else
			$model->orderBy('creation_date DESC');
		$model = $model->all();

		return $this->render('archive_popular', [
			'model' => $model,
		]);
	}
}