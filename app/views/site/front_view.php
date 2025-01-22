<?php
/**
 * Core Pages (core-pages)
 * @var $this app\components\View
 * @var $this ommu\core\controllers\page\AdminController
 * @var $model ommu\core\models\CorePages
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 2 October 2017, 16:31 WIB
 * @modified date 31 January 2019, 16:38 WIB
 * @link https://github.com/ommu/mod-core
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title->message; ?>

<div class="core-pages-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'page_id',
		[
			'attribute' => 'publish',
			'value' => $model->quickAction(Url::to(['publish', 'id'=>$model->primaryKey]), $model->publish),
			'format' => 'raw',
		],
		[
			'attribute' => 'name_i',
			'value' => $model->name_i,
		],
		[
			'attribute' => 'desc_i',
			'value' => $model->desc_i,
			'format' => 'html',
		],
		[
			'attribute' => 'quote_i',
			'value' => $model->quote_i,
		],
		[
			'attribute' => 'media',
			'value' => function ($model) {
				$uploadPath = $model::getUploadPath(false);
				return $model->media ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $model->media])), ['alt'=>$model->media, 'class'=>'mb-3']).'<br/>'.$model->media : '-';
			},
			'format' => 'html',
		],
		[
			'attribute' => 'media_show',
			'value' => $model::getMediaShow($model->media_show),
		],
		[
			'attribute' => 'media_type',
			'value' => $model::getMediaType($model->media_type),
		],
		[
			'attribute' => 'creation_date',
			'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
		],
		[
			'attribute' => 'creationDisplayname',
			'value' => isset($model->creation) ? $model->creation->displayname : '-',
		],
		[
			'attribute' => 'modified_date',
			'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
		],
		[
			'attribute' => 'modifiedDisplayname',
			'value' => isset($model->modified) ? $model->modified->displayname : '-',
		],
		[
			'attribute' => 'updated_date',
			'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
		],
		[
			'attribute' => 'slug',
			'value' => $model->slug ? $model->slug : '-',
		],
		[
			'attribute' => 'views',
			'value' => function ($model) {
				$views = $model->view->views;
				return Html::a($views, ['page/view/manage', 'page'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} views', ['count'=>$views])]);
			},
			'format' => 'html',
		],
	],
]) ?>

</div>