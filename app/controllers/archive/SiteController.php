<?php
/**
 * SiteController
 * @var $this app\components\View
 * @var $this siks\app\controllers\archive\SiteController
 * @var $model ommu\archive\models\Archives
 * 
 * Reference start
 * TOC :
 *	Index
 *	View
 *	Preview
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 20 May 2019, 11:29 WIB
 * @link https://bitbucket.org/ommu/siks
 *
 */

namespace siks\app\controllers\archive;

use Yii;
use yii\helpers\Url;
use app\components\Controller;
use ommu\archive\models\Archives;
use ommu\archive\models\ArchiveViewHistory;
use ommu\archive\models\ArchiveViews;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\Inflector;
use yii\helpers\ArrayHelper;

class SiteController extends Controller
{
    use \ommu\traits\FileTrait;

	public static $backoffice = false;

	/**
	 * Renders the index view for the module
	 * @return string
	 */
	public function actionIndex()
	{
        $popular = false;
        $attributes = Yii::$app->request->get();
        $by = Yii::$app->request->get('by');
        $searchTitle = Yii::$app->request->get('title');
        $searchLevel = Yii::$app->request->get('level_id');
        $searchDocument = Yii::$app->request->get('document');
        $searchCreationDate = Yii::$app->request->get('creation_date');

        if(in_array($this->type, ['default', 'latest', 'gallery']) ) {
            $query = Archives::find()
                ->alias('t')
                ->andFilterWhere(['t.publish' => 1]);
            $fond = true;
            if(($level = Yii::$app->request->get('level')) != null) {
                if($level != 1) {
                    $fond = false;
                }
                if($level != 'all') {
                    $query->andFilterWhere(['t.level_id' => $level]);
                }
            } else {
                if($this->type == 'default') {
                    unset($attributes['page']);
                    unset($attributes['per-page']);
                    if(empty($attributes)) {
                        $query->andFilterWhere(['t.level_id' => 1]);
                    }
                }
            }
            // title search
            if($searchTitle != null) {
                $query->andFilterWhere(['like', 't.title', $searchTitle]);
            }
            // level search
            if($searchLevel != null) {
                $query->andFilterWhere(['t.level_id' => $searchLevel]);
            }
            // has document search (if gallery, default and latest)
            if($this->type == 'gallery' || ($this->type != 'gallery' && $searchDocument != null && $searchDocument == 1)) {
                $query->andWhere(['<>', 't.archive_file', '']);
            }
            // created date search
            if($searchCreationDate != null) {
                $searchCreationDateArray = $this->view->formatFileType($searchCreationDate, true, 'to');
                if(count($searchCreationDateArray) == 2) {
                    $query->andFilterWhere(['>=', 't.creation_date', Yii::$app->formatter->asDate($searchCreationDateArray[0], 'php:Y-m-d')]);
                    $query->andFilterWhere(['<=', 't.creation_date', Yii::$app->formatter->asDate($searchCreationDateArray[1], 'php:Y-m-d')]);
                } elseif(count($searchCreationDateArray) == 1) {
                    $query->andFilterWhere(['t.creation_date' => Yii::$app->formatter->asDate($searchCreationDateArray[0], 'php:Y-m-d')]);
                }
            }
            // if gallery condition
            if($this->type == 'gallery') {
                $setting = \ommu\archive\models\ArchiveSetting::find()
                    ->select(['image_type'])
                    ->where(['id' => 1])
                    ->one();
        
                $imageFileType = $this->formatFileType($setting->image_type);

                if(!empty($imageFileType)) {
                    $criteria = [];
                    foreach ($imageFileType as $key => $val) {
                        $criteria[$key] = ['like', 't.archive_file', '%'.$val, false];
                    }
                    $query->andWhere(ArrayHelper::merge(['or'], $criteria));
                }
            }
            // if latest condition
            if($this->type == 'latest') {
                // filter search
                if($by == null || ($by != null && $by == 'today')) {
                    $today = new Expression('CURDATE()');
                    $query->andFilterWhere(['DATE(t.creation_date)' => $today]);
                } else if($by == 'yesterday') {
                    $yesterday = new Expression('ADDDATE(CURDATE(), INTERVAL -1 DAY)');
                    $query->andFilterWhere(['DATE(t.creation_date)' => $yesterday]);
                } else if($by == 'week') {
                    $week = new Expression('YEARWEEK(ADDDATE(CURDATE(), INTERVAL -1 WEEK))');
                    $query->andFilterWhere(['YEARWEEK(t.creation_date)' => $week]);
                } else if($by == 'month') {
                    if(($thisMonth = (int)Yii::$app->formatter->asDate('now', 'php:m')) == 1) {
                        $year = new Expression('YEAR(ADDDATE(CURDATE(), INTERVAL -1 YEAR))');
                    } else {
                        $year = new Expression('YEAR(CURDATE())');
                    }
                    $month = new Expression('MONTH(ADDDATE(CURDATE(), INTERVAL -1 MONTH))');
                    $query->andFilterWhere(['year(t.creation_date)' => $year])
                        ->andFilterWhere(['month(t.creation_date)' => $month]);
                } else if($by == 'year') {
                    $year = new Expression('YEAR(ADDDATE(CURDATE(), INTERVAL -1 YEAR))');
                    $query->andFilterWhere(['year(t.creation_date)' => $year]);
                }
            }
            $query->orderBy('t.creation_date DESC, t.id DESC');

        } else {
            $popular = true;
            $query = ArchiveViewHistory::find()
                ->alias('t')
                ->select(['t.view_id', 't.view_date', 'count(t.id) as counts'])
                ->joinWith([
                    'view view',
                    'view.archive archive'
                ])
                ->andFilterWhere(['view.publish' => 1])
                ->andFilterWhere(['archive.publish' => 1]);
            // title search
            if($searchTitle != null) {
                $query->andFilterWhere(['like', 'archive.title', $searchTitle]);
            }
            // level search
            if($searchLevel != null) {
                $query->andFilterWhere(['archive.level_id' => $searchLevel]);
            }
            // has document search
            if($searchDocument != null && $searchDocument == 1) {
                $query->andWhere(['<>', 'archive.archive_file', '']);
            }
            // filter search
            if($by == null || ($by != null && $by == 'today')) {
                $today = new Expression('CURDATE()');
                $query->andFilterWhere(['DATE(t.view_date)' => $today]);
            } else if($by == 'yesterday') {
                $yesterday = new Expression('ADDDATE(CURDATE(), INTERVAL -1 DAY)');
                $query->andFilterWhere(['DATE(t.view_date)' => $yesterday]);
            } else if($by == 'week') {
                $week = new Expression('YEARWEEK(CURDATE())');
                $query->andFilterWhere(['YEARWEEK(t.view_date)' => $week]);
            } else if($by == 'month') {
                $year = new Expression('YEAR(CURDATE())');
                $month = new Expression('MONTH(CURDATE())');
                $query->andFilterWhere(['year(t.view_date)' => $year])
                    ->andFilterWhere(['month(t.view_date)' => $month]);
            } else if($by == 'year') {
                $year = new Expression('YEAR(CURDATE())');
                $query->andFilterWhere(['year(t.view_date)' => $year]);
            }
            $query->groupBy('view.archive_id')
                ->orderBy('counts DESC');
        }

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        $getModels = $this->getDataModel($dataProvider->getModels(), $popular);
        
        $render = 'front_index';
        if($this->type == 'gallery') {
            $getModels = $dataProvider->getModels();
            $render = 'front_gallery';
        } else if($this->type != 'default') {
            $render = 'front_latest';
        }

        $title = Yii::t('app', 'Archives');
        if($this->type == 'default') {
            if(($level = Yii::$app->request->get('level')) != null) {
                if($level != 'all') {
                    $level = \ommu\archive\models\ArchiveLevel::findOne($level);
                    $title = Yii::t('app', 'Archives: {level}', ['level' => $level->level_name_i]);
                } else {
                    $title = Yii::t('app', 'All Archives');
                }
            }
        } elseif($this->type == 'latest') {
            $title = Yii::t('app', 'Latest Archives');
            if($by != null) {
                $title = Yii::t('app', 'Latest Archives: {by}', ['by' => ucwords($this->getTitle($by))]);
            }
        } elseif($this->type == 'popular') {
            $title = Yii::t('app', 'Popular Archives');
            if($by != null) {
                $title = Yii::t('app', 'Popular Archives: {by}', ['by' => ucwords($this->getTitle($by))]);
            }
        }

        $this->view->cards = false;
		$this->view->title = $title;
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render($render, [
            'dataProvider' => $dataProvider,
            'models' => $getModels,
            'fond' => $fond,
            'popular' => $popular,
            'isArchive' => $this->type == 'default' ? true : false,
		]);
	}

	/**
	 * Renders the index view for the module
	 * @return string
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);
        ArchiveViews::insertView($model->id);

		$isFond = (strtolower($model->levelTitle->message) == 'fond') ? true : false;
		$isItem = empty($parent->level->child) ? true : false;
		
		$this->layout = 'default';
		$this->view->title = strtoupper($model->levelTitle->message).' '.$model::parseCode($model, ['short'=>true]);
		$this->view->description = $model::htmlHardDecode($model->title);
		$this->view->keywords = '';
		return $this->render('front_view', [
			'model' => $model,
			'isFond' => $isFond,
			'isItem' => $isItem,
		]);
	}

	/**
	 * Finds the Archives model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Archives the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = Archives::findOne(['id' => $id, 'publish' => 1])) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getViewPath()
	{
		return Yii::getAlias('@siks/app/views/archive');
	}

	/**
	 * Displays a single Archives model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionData($id)
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

		$model = Archives::findOne($id);

		if($model == null) return [];

		$codes = [];
		$result[] = $this->getDataTree($model, $codes);

		return $result;
	}

	/**
	 * Displays a single Archives model.
	 * @param integer $id
	 * @return mixed
	 */
	public function getDataTree($model, $codes)
	{
		$slug = Inflector::slug($model->title);
		$title = $model::htmlHardDecode($model->title);
		$data = [
			'id' => $model->id,
			'code' => $model->code,
			'level' => $model->levelTitle->message,
			'label' => $title,
			'inode' => $model->getArchives('count') ? true : false,
			'view-url' => Url::to(['view', 'id'=>$model->id, 't'=>$slug]),
		];
		if(!empty($codes))
			$data = ArrayHelper::merge($data, ['open'=>true, 'branch'=>[$codes]]);
		
		if(isset($model->parent))
			$data = $this->getDataTree($model->parent, $data);

		return $data;
	}

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'default';
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderes()
    {
        return [
            'today',
            'yesterday',
            'week',
            'month',
            'year',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle($by)
    {
        if($by == 'today') {
            return Yii::t('app', 'today\'s');
        } elseif($by == 'yesterday') {
            return Yii::t('app', 'yesterday\'s');
        } elseif($by == 'week') {
            return Yii::t('app', 'this week');
        } elseif($by == 'month') {
            return Yii::t('app', 'this month');
        } elseif($by == 'year') {
            return Yii::t('app', 'this year');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDataModel($models, $popular=false)
    {
        $setting = \ommu\archive\models\ArchiveSetting::find()
            ->select(['reference_code_sikn', 'reference_code_separator', 'short_code', 'maintenance_mode'])
            ->where(['id' => 1])
            ->one();

        $contentData = [];
        if(!empty($models)) {
            if($popular == false) {
                foreach ($models as $key => $val) {
                    $creationDate = Yii::$app->formatter->asDate($val->creation_date, 'php:Y-m-d');
                    if($setting->short_code)
                        $code = !$setting->maintenance_mode ? $val->code : $val->confirmCode;
                    else {
                        $code = join($setting->reference_code_separator, ArrayHelper::map($val->referenceCode, 'level', 'code'));
                        if($setting->maintenance_mode)
                            $code = join($setting->reference_code_separator, ArrayHelper::map($val->referenceCode, 'level', 'confirmCode'));
                    }
                    if($setting->reference_code_sikn) {
                        $code = $setting->reference_code_sikn.' '.$code;
                    }
                    $contentData[$creationDate][$key] = [
                        'id' => $val->id,
                        'title' => $val->title,
                        'code' => $code,
                        'levelId' => $val->level_id,
                        'levelName' => $val->levelTitle->message,
                        'archiveDate' => $val->archive_date,
                        'archiveFile' => $val->archive_file,
                        'creationDate' => $val->creation_date,
                        'views' => $val->grid->view,
                    ];
                }

            } else {
                foreach ($models as $key => $val) {
                    $creationDate = Yii::$app->formatter->asDate($val->view_date, 'php:Y-m-d');
                    if($setting->short_code)
                        $code = !$setting->maintenance_mode ? $val->archive->code : $val->archive->confirmCode;
                    else {
                        $code = join($setting->reference_code_separator, ArrayHelper::map($val->archive->referenceCode, 'level', 'code'));
                        if($setting->maintenance_mode)
                            $code = join($setting->reference_code_separator, ArrayHelper::map($val->archive->referenceCode, 'level', 'confirmCode'));
                    }
                    if($setting->reference_code_sikn) {
                        $code = $setting->reference_code_sikn.' '.$code;
                    }
                    $contentData[$creationDate][$key] = [
                        'id' => $val->view->archive_id,
                        'title' => $val->archive->title,
                        'code' => $code,
                        'levelId' => $val->archive->level_id,
                        'levelName' => $val->archive->levelTitle->message,
                        'archiveDate' => $val->archive->archive_date,
                        'archiveFile' => $val->archive->archive_file,
                        'creationDate' => $val->archive->creation_date,
                        'views' => $val->counts,
                        'allViews' => $val->archive->grid->view,
                    ];
                }
            }
        }

        return $contentData;
    }

    /**
     * {@inheritdoc}
     */
    public function actionPreview($id)
    {
        $model = $this->findModel($id);
        ArchiveViews::insertView($model->id);

        $this->view->title = Yii::t('app', 'Document {level-name}: {code}', ['level-name'=>$model->levelTitle->message, 'code'=>$model->code]);
        $this->view->description = '';
        $this->view->keywords = '';
        return $this->oRender('front_preview_document', [
            'model' => $model,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function actionTree($id)
    {
        $model = $this->findModel($id);
        ArchiveViews::insertView($model->id);

        $this->view->title = Yii::t('app', 'Tree {level-name}: {code}', ['level-name'=>$model->levelTitle->message, 'code'=>$model->code]);
        $this->view->description = '';
        $this->view->keywords = '';
        return $this->oRender('front_preview_tree', [
            'model' => $model,
        ]);
    }
}
