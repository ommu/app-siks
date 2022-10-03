<?php
/**
 * ArchivePopular
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2020 OMMU (www.ommu.co)
 * @created date 3 March 2020, 18:59 WIB
 * @link https://bitbucket.org/ommu/bpadjogja-portal
 * 
 */

namespace siks\app\components;

use Yii;
use ommu\archive\models\ArchiveViewHistory;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class ArchivePopular extends \yii\base\Widget
{
    /**
     * {@inheritdoc}
     */
    public $orderBy = [
        'today',
        'yesterday',
        'week',
        'month',
        'year',
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
            $model = ArchiveViewHistory::find()
                ->alias('t')
                ->select(['t.view_id', 'count(t.id) as counts'])
                ->joinWith([
                    'view view',
                    'view.archive archive'
                ])
                ->andWhere(['view.publish' => 1])
                ->andWhere(['archive.publish' => 1]);
            if(!empty($this->ignoreLevel))
                $model->andWhere(['NOT IN', 'level_id', $this->ignoreLevel]);
            if($val == 'year') {
                $year = new Expression('YEAR(CURDATE())');
                $model->andWhere(['year(t.view_date)' => $year]);
            } else if($val == 'month') {
                $year = new Expression('YEAR(CURDATE())');
                $month = new Expression('MONTH(CURDATE())');
                $model->andWhere(['year(t.view_date)' => $year])
                    ->andWhere(['month(t.view_date)' => $month]);
            } else if($val == 'week') {
                $week = new Expression('YEARWEEK(CURDATE())');
                $model->andWhere(['YEARWEEK(t.view_date)' => $week]);
            } else if($val == 'yesterday') {
                $yesterday = new Expression('ADDDATE(CURDATE(), INTERVAL -1 DAY)');
                $model->andWhere(['DATE(t.view_date)' => $yesterday]);
            } else if($val == 'today') {
                $today = new Expression('CURDATE()');
                $model->andWhere(['DATE(t.view_date)' => $today]);
            }
            $model = $model->groupBy('view.archive_id')
                ->orderBy('counts DESC')
                ->limit($this->limit)
                ->all();

            if($model != null) {
                foreach ($model as $key => $row) {
                    $title = $row->view::htmlHardDecode($row->archive->title);
                    if($setting->short_code)
                        $code = !$setting->maintenance_mode ? $row->archive->code : $row->archive->confirmCode;
                    else {
                        $code = join($setting->reference_code_separator, ArrayHelper::map($row->archive->referenceCode, 'level', 'code'));
                        if($setting->maintenance_mode)
                            $code = join($setting->reference_code_separator, ArrayHelper::map($row->archive->referenceCode, 'level', 'confirmCode'));
                    }
                    if($setting->reference_code_sikn) {
                        $code = $setting->reference_code_sikn.' '.$code;
                    }
                    $this->archiveContent[$val][$key] = [
                        'id' => $row->view->archive_id,
                        'title' => $title,
                        'levelId' => $row->archive->level_id,
                        'levelName' => $row->archive->levelTitle->message,
                        'code' => $code,
                        'archiveDate' => $row->archive->archive_date,
                        'creationDate' => $row->archive->creation_date,
                        'views' => $row->archive->grid->view,
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
        return $this->render('archive_popular', [
            'orderBy' => $this->orderBy,
            'archiveContent' => $this->archiveContent,
        ]);
    }
}