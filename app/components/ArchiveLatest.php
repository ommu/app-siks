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
use ommu\archive\models\Archives;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class ArchiveLatest extends \yii\base\Widget
{
    /**
     * {@inheritdoc}
     */
    public $orderBy = [
        'year',
        'month',
        'week',
        'yesterday',
        'today',
    ];
	/**
	 * {@inheritdoc}
	 */
	public $ignoreLevel = [];
	/**
	 * {@inheritdoc}
	 */
	public $limit = 5;
    /**
     * {@inheritdoc}
     */
    public $archiveContent = [];

    /**
     * {@inheritdoc}
     */
	public function init()
	{
        $setting = \ommu\archive\models\ArchiveSetting::find()
            ->select(['reference_code_sikn', 'reference_code_separator', 'short_code', 'maintenance_mode'])
            ->where(['id' => 1])
            ->one();

        foreach($this->orderBy as $val) {
            $model = Archives::find()
                ->alias('t')
                ->select(['t.id', 't.level_id', 't.title', 't.code', 't.archive_date', 't.creation_date'])
                ->andWhere(['t.publish' => 1]);
            if(!empty($this->ignoreLevel))
                $model->andWhere(['NOT IN', 'level_id', $this->ignoreLevel]);
            if($val == 'year') {
                $year = new Expression('YEAR(ADDDATE(CURDATE(), INTERVAL -1 YEAR))');
                $model->andWhere(['year(t.creation_date)' => $year]);
            } else if($val == 'month') {
                if(($thisMonth = (int)Yii::$app->formatter->asDate('now', 'php:m')) == 1) {
                    $year = new Expression('YEAR(ADDDATE(CURDATE(), INTERVAL -1 YEAR))');
                } else {
                    $year = new Expression('YEAR(CURDATE())');
                }
                $month = new Expression('MONTH(ADDDATE(CURDATE(), INTERVAL -1 MONTH))');
                $model->andWhere(['year(t.creation_date)' => $year])
                    ->andWhere(['month(t.creation_date)' => $month]);
            } else if($val == 'week') {
                $week = new Expression('YEARWEEK(ADDDATE(CURDATE(), INTERVAL -1 WEEK))');
                $model->andWhere(['YEARWEEK(t.creation_date)' => $week]);
            } else if($val == 'yesterday') {
                $yesterday = new Expression('ADDDATE(CURDATE(), INTERVAL -1 DAY)');
                $model->andWhere(['DATE(t.creation_date)' => $yesterday]);
            } else if($val == 'today') {
                $today = new Expression('CURDATE()');
                $model->andWhere(['DATE(t.creation_date)' => $today]);
            }
            $model = $model->orderBy('t.creation_date DESC, t.id DESC')
                ->limit($this->limit)
                ->all();

            if($model != null) {
                foreach ($model as $key => $row) {
                    $title = $row::htmlHardDecode($row->title);
                    if($setting->short_code)
                        $code = !$setting->maintenance_mode ? $row->code : $row->confirmCode;
                    else {
                        $code = join($setting->reference_code_separator, ArrayHelper::map($row->referenceCode, 'level', 'code'));
                        if($setting->maintenance_mode)
                            $code = join($setting->reference_code_separator, ArrayHelper::map($row->referenceCode, 'level', 'confirmCode'));
                    }
                    if($setting->reference_code_sikn) {
                        $code = $setting->reference_code_sikn.' '.$code;
                    }
                    $this->archiveContent[$val][$key] = [
                        'id' => $row->id,
                        'title' => $title,
                        'levelId' => $row->level_id,
                        'levelName' => $row->levelTitle->message,
                        'code' => $code,
                        'archiveDate' => $row->archive_date,
                        'creationDate' => $row->creation_date,
                        'views' => $row->grid->view,
                    ];
                }
            } else {
                $this->archiveContent[$val] = [];
            }
        }
	}

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->render('archive_latest', [
            'orderBy' => $this->orderBy,
            'archiveContent' => $this->archiveContent,
        ]);
    }
}