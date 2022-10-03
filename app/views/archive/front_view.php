<?php
/**
 * @var $this app\components\View
 * @var $this siks\app\controllers\archive\SiteController
 * @var $model ommu\archive\models\Archives
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 20 May 2019, 11:29 WIB
 * @link https://bitbucket.org/ommu/siks
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\data\ActiveDataProvider;
use app\components\widgets\ListView;
use yii\widgets\Pjax;

\siks\app\assets\AciTreeAsset::register($this);

$treeDataUrl = Url::to(['data', 'id'=>$model->id]);
$js = <<<JS
	var treeDataUrl = '$treeDataUrl';
	var selectedId = '$model->id';
JS;
$this->registerJs($js, \yii\web\View::POS_HEAD);

$title = $model::htmlHardDecode($model->title);
?>

<div class="hero-banner bg-primary-dark d-flex flex-row align-items-center" style="height:250px;">
	<div class="<?php echo in_array($this->subLayout, ['fixed','mini']) ? 'container' : 'container-fluid';?> page__container">
		<div class="d-flex flex-column">
			<div class="mb-2">
				<div class="badge badge-primary text-lowercase"><?php echo $model->levelTitle->message;?></div>

				<?php if($model->archive_date) {?>
				<div class="badge badge-primary text-lowercase"><?php echo strtolower($model->levelTitle->message) != 'fond' ? Yii::$app->formatter->asDate($model->archive_date, 'php:Y') : $model->archive_date;?></div>
				<?php }
				
				if(count($subject = $model->getSubjects(true, 'title')) != 0) {
					foreach ($subject as $key => $val) {?>
						<div class="badge badge-primary text-lowercase"><?php echo $val;?></div>
				<?php }
				}
				
				if(count($function = $model->getFunctions(true, 'title')) != 0) {
					foreach ($function as $key => $val) {?>
						<div class="badge badge-primary text-lowercase"><?php echo $val;?></div>
				<?php }
				}?>
			</div>
			<h1 class="text-white mb-0"><?php echo $model::parseCode($model, ['short'=>true]);?></h1>
			<!-- <p class="lead text-white">ommu</p> -->
			<div class="my-2 text-white d-flex">
				<strong class="mr-4 text-uppercase"><i class="material-icons icon-16pt icon-light">folder</i> <?php echo $model->levelTitle->message;?></strong>
				<?php if($model->archive_date) {?>
				<strong><i class="material-icons icon-16pt icon-light">date_range</i> <?php echo strtolower($model->levelTitle->message) != 'fond' ? Yii::$app->formatter->asDate($model->archive_date, 'long') : $model->archive_date;?></strong>
				<?php }?>
			</div>
			<!-- <div class="mt-1">
				<div class="btn btn-light btn-rounded mr-2">Start Course</div>
				<div class="btn btn-outline-light btn-rounded"><i class="material-icons">local_activity</i> Add to list</div>
			</div> -->
		</div>
	</div>
</div>

<div class="<?php echo in_array($this->subLayout, ['fixed','mini']) ? 'container' : 'container-fluid';?> page__container">
	<div class="row">
		<div class="col-md-4 order-12">
			<div class="card card-margin-md-negative-80 mb-4">
				<ul class="list-group list-group-flush">
					<?php if(count($repository = $model->getRepositories(true, 'title')) != 0) {?>
					<li class="list-group-item">
						<span class="text-muted d-block mb-1"><?php echo $model->getAttributeLabel('repository');?></span>
						<?php echo Html::ul($repository, ['encode'=>false, 'class'=>'list-boxed']);?>
					</li>
					<?php }?>

					<?php if(count($creator = $model->getCreators(true, 'title')) != 0) {?>
					<li class="list-group-item">
						<span class="text-muted d-block mb-1"><?php echo $model->getAttributeLabel('creator');?></span>
						<?php echo Html::ul($creator, ['encode'=>false, 'class'=>'list-boxed']);?>
					</li>
					<?php }?>

					<?php if($model->archive_type && in_array('archive_type', $model->level->field)) {?>
					<li class="list-group-item">
						<span class="text-muted d-block mb-1"><?php echo $model->getAttributeLabel('archive_type');?></span>
						<strong><?php echo $model::getArchiveType($model->archive_type);?></strong>
					</li>
					<?php }?>

					<?php if(count($media = $model->getMedias(true, 'media')) != 0 && in_array('media', $model->level->field)) {?>
					<li class="list-group-item">
						<span class="text-muted d-block mb-1"><?php echo $model->getAttributeLabel('media');?></span>
						<?php echo Html::ul($media, ['encode'=>false, 'class'=>'list-boxed']);?>
					</li>
					<?php }?>

					<?php if(count($medium = $model->getChilds(['back3nd'=>false])) != 0) {?>
					<li class="list-group-item">
						<span class="text-muted d-block mb-1"><?php echo $model->getAttributeLabel('medium');?></span>
						<?php echo $model::parseChilds($medium);?>
					</li>
					<?php }?>

					<?php if(!Yii::$app->user->isGuest && ($location = $model->getRelatedLocation(false)) != null) {?>
					<li class="list-group-item">
						<span class="text-muted d-block mb-1"><?php echo $model->getAttributeLabel('location');?></span>
						<?php echo $model::parseLocation($location);?>
					</li>
					<?php }?>

					<li class="list-group-item">
						<span class="text-muted d-block mb-1"><?php echo $model->getAttributeLabel('published_date');?></span>
						<?php echo $model->getAttributeLabel('creation_id').' '.Yii::$app->formatter->asDatetime($model->creation_date, 'long');
						if($model->creation_date != $model->modified_date && ($modified_date = Yii::$app->formatter->asDatetime($model->modified_date, 'long')) != '-')
							echo '<br/>'.$model->getAttributeLabel('modified_id').' '.$modified_date;?>
					</li>

					<li class="list-group-item">
						<a href="" class="btn btn-facebook btn-rounded-social">
							<svg width="14px" style="fill: currentColor;" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
								<title>Facebook icon</title>
								<path d="M22.676 0H1.324C.593 0 0 .593 0 1.324v21.352C0 23.408.593 24 1.324 24h11.494v-9.294H9.689v-3.621h3.129V8.41c0-3.099 1.894-4.785 4.659-4.785 1.325 0 2.464.097 2.796.141v3.24h-1.921c-1.5 0-1.792.721-1.792 1.771v2.311h3.584l-.465 3.63H16.56V24h6.115c.733 0 1.325-.592 1.325-1.324V1.324C24 .593 23.408 0 22.676 0" />
							</svg>
						</a>
						<a href="" class="btn btn-twitter btn-rounded-social">
							<svg width="14px" style="fill: currentColor;" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
								<title>Twitter icon</title>
								<path d="M23.954 4.569c-.885.389-1.83.654-2.825.775 1.014-.611 1.794-1.574 2.163-2.723-.951.555-2.005.959-3.127 1.184-.896-.959-2.173-1.559-3.591-1.559-2.717 0-4.92 2.203-4.92 4.917 0 .39.045.765.127 1.124C7.691 8.094 4.066 6.13 1.64 3.161c-.427.722-.666 1.561-.666 2.475 0 1.71.87 3.213 2.188 4.096-.807-.026-1.566-.248-2.228-.616v.061c0 2.385 1.693 4.374 3.946 4.827-.413.111-.849.171-1.296.171-.314 0-.615-.03-.916-.086.631 1.953 2.445 3.377 4.604 3.417-1.68 1.319-3.809 2.105-6.102 2.105-.39 0-.779-.023-1.17-.067 2.189 1.394 4.768 2.209 7.557 2.209 9.054 0 13.999-7.496 13.999-13.986 0-.209 0-.42-.015-.63.961-.689 1.8-1.56 2.46-2.548l-.047-.02z" />
							</svg>
						</a>
						<a href="" class="btn btn-secondary btn-rounded-social">
							<i class="material-icons">mail</i>
						</a>
					</li>
				</ul>
			</div>

			<div class="card">
				<ul class="list-group list-group-flush mb-4">
					<li class="list-group-item bg-light">
						<strong><?php echo Yii::t('app', 'Holdings');?></strong>
					</li>
					<li class="list-group-item">
						<div id="tree" class="aciTree"></div>
					</li>
				</ul>
			</div>

			<ul class="list-group list-group-flush">
				<?php if(count($subject = $model->getSubjects(true, 'title')) != 0) {?>
				<li class="list-group-item p-0 mb-3 transparent border-none">
					<span class="text-muted d-block mb-2"><i class="material-icons icon-16pt icon-light">subject</i> <?php echo $model->getAttributeLabel('subject');?></span>
					<?php echo implode(', ', $subject);?>
				</li>
				<?php }?>

				<?php if(count($function = $model->getFunctions(true, 'title')) != 0) {?>
				<li class="list-group-item p-0 transparent border-none">
					<span class="text-muted d-block mb-2"><i class="material-icons icon-16pt icon-light">functions</i> <?php echo $model->getAttributeLabel('function');?></span>
					<?php echo implode(', ', $function);?>
				</li>
				<?php }?>
			</ul>
		</div>
		<div class="col-md-8">
			<div class="page__heading">
				<div class="mb-2"><i class="material-icons icon-16pt icon-muted mr-1">filter_1</i> <strong class="text-dark-gray text-uppercase"><?php echo $model->getAttributeLabel('code');?></strong></div>
				<?php echo $model::parseCode($model);?>

				<div class="mb-2 mt-3"><i class="material-icons icon-16pt icon-muted mr-1">filter_2</i> <strong class="text-dark-gray text-uppercase"><?php echo Yii::t('app', 'Title');?></strong></div>
				<?php echo $model->title;?>

				<div class="mb-2 mt-3"><i class="material-icons icon-16pt icon-muted mr-1">filter_3</i> <strong class="text-dark-gray text-uppercase"><?php echo $model->getAttributeLabel('level_id');?></strong></div>
				<?php echo $model->levelTitle->message;?>

				<?php if($model->archive_date) {?>
				<div class="mb-2 mt-3"><i class="material-icons icon-16pt icon-muted mr-1">filter_4</i> <strong class="text-dark-gray text-uppercase"><?php echo $model->getAttributeLabel('archive_date');?></strong></div>
				<?php echo strtolower($model->levelTitle->message) != 'fond' ? Yii::$app->formatter->asDate($model->archive_date, 'long') : $model->archive_date;?>
				<?php }?>
			</div>

			<?php if($model->archive_file != '') {
				$uploadPath = join('/', [$model::getUploadPath(false), $model->id]);
				$extension = pathinfo($model->archive_file, PATHINFO_EXTENSION);
				$setting = $model->getSetting(['image_type', 'document_type', 'maintenance_image_path', 'maintenance_document_path']);
				$imageFileType = $model->formatFileType($setting->image_type);
				$documentFileType = $model->formatFileType($setting->document_type);

				if($model->isNewFile)
					$uploadPath = join('/', [$model::getUploadPath(false), $model->id]);
				else {
					if(in_array($extension, $imageFileType))
						$uploadPath = join('/', [$model::getUploadPath(false), $setting->maintenance_image_path]);
					if(in_array($extension, $documentFileType))
						$uploadPath = join('/', [$model::getUploadPath(false), $setting->maintenance_document_path]);
				}
				$filePath = Url::to(join('/', ['@webpublic', $uploadPath, $model->archive_file]));

				if(in_array($extension, $imageFileType)) {
					echo Html::tag('div', Html::tag('div', Html::img($filePath, ['alt'=>$model->archive_file, 'width'=>'100%', 'max-height'=>'720']), ['class'=>'card-header p-0']).'<div class=""></div>', ['class'=>'card d-block']);
				}

				if(in_array($extension, $documentFileType)) {
					echo \app\components\widgets\PreviewPDF::widget([
						'url' => $filePath,
						'options' => [
							'class' => 'card d-block text-center',
						],
						'navigationOptions' => [
							'class' => 'card-header summary p-4 d-flex',
							'summary' => [
								'class' => 'ml-auto mr-auto pt-2',
							],
							'prev' => [
								'class' => 'btn btn-primary',
							],
							'next' => [
								'class' => 'btn btn-primary',
							],
						],
					]);
				}
			}

			if(($archives = $model->getArchives('count', 1)) != 0) {
				Pjax::begin();

				echo $this->render('_search', [
					'model'=>$model, 
					'placeholder'=>Yii::t('app', 'Search in {level} {title}', [
						'level' => $model->levelTitle->message,
						'title' => $title,
					]),
				]);

				$query = $model->getArchives('relation', 1);
				if(($titleSearch = Yii::$app->request->get('title')) != null)
					$query->andWhere(['title' => $titleSearch]);
				$dataProvider = new ActiveDataProvider([
					'query' => $query,
				]);

				echo ListView::widget([
					'dataProvider' => $dataProvider,
					'options' => [
						'tag' => 'ul',
						'class' => 'list-group list-lessons',
					],
					'summaryOptions' => [
						'class' => 'summary mr-auto pt-1',
					],
					'itemOptions' => [
						'tag' => 'li',
						'class' => 'list-group-item d-flex',
					],
					'layout' => "<div class=\"card\">{items}</div>\n<div class=\"d-flex\">{summary}\n{pager}</div>",
					'itemView' => '_view_child',
				]);
				
				Pjax::end();
			}?>
		</div>
	</div>
</div>