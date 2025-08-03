<?php
/**
 * @var $this app\components\View
 * @var $this siks\app\controllers\archive\LatestController
 * @var $model ommu\archive\models\Archives
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 20 May 2019, 11:29 WIB
 * @link https://bitbucket.org/ommu/siks
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Inflector;
use yii\helpers\ArrayHelper;

$setting = \ommu\archive\models\ArchiveSetting::find()
    ->select(['reference_code_sikn', 'reference_code_separator', 'short_code', 'image_type', 'document_type', 'maintenance_mode', 'maintenance_image_path', 'maintenance_document_path'])
    ->where(['id' => 1])
    ->one();

$imageFileType = $this->formatFileType($setting->image_type);
$documentFileType = $this->formatFileType($setting->document_type);
?>

<div class="row">
	<?php 
	foreach ($models as $val) {
        $title = $this::htmlHardDecode($val->title);
        $url = Url::to(['/archive/site/preview', 'id'=>$val->id, 't'=>Inflector::slug($title)]);

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

        $extension = pathinfo($val->archive_file, PATHINFO_EXTENSION);
        $isImage = in_array($extension, $imageFileType) ? true : false;

        // if($val->isNewFile) {
        //     $uploadPath = join('/', [$val::getUploadPath(), $val->id]);
        // } else {
        //     $uploadPath = join('/', [$val::getUploadPath(), ($isImage == true ? $setting->maintenance_image_path : $setting->maintenance_document_path)]);
        // }
        // $fileExists = $val->archive_file != '' && file_exists(join('/', [$uploadPath, $val->archive_file])) ? true : false;

        if($val->isNewFile) {
            $uploadPath = join('/', [$val::getUploadPath(false), $val->id]);
        } else {
            $uploadPath = join('/', [$val::getUploadPath(false), ($isImage == true ? $setting->maintenance_image_path : $setting->maintenance_document_path)]);
        }
        $filePath = Url::to(join('/', ['@webpublic', $uploadPath, $val->archive_file])); ?>
	<div class="col-sm-6 col-md-4">
		<div class="card stories-card-popular">
			<img src="<?php echo $filePath;?>" alt="<?php echo $title;?>" class="card-img">
			<div class="stories-card-popular__content shadow-content">
				<div class="card-body d-flex align-items-center position-absolute text-white">
                    <i class="material-icons mr-1" style="font-size: inherit;">remove_red_eye</i>
                    <small><?php echo $val->grid->view;?></small>
				</div>
				<div class="stories-card-popular__title card-body">
					<small class="text-muted text-uppercase"><?php echo strtoupper($val['levelName']);?></small>
					<h5 class="card-title m-0">
						<?php echo Html::a($code, $url, ['title'=>$title, 'class'=>'modal-btn']);?>
					</h5>
                    <?php if($val->archive_date && $val->archive_date != '-') {?>
					<small class="text-white text-lowercase d-inline-block mt-1">
                        <i class="material-icons icon-16pt icon-white mr-1">date_range</i>
                        <?php echo $val->level_id == 1 ? Yii::t('app', 'Archive year {archiveDate}', ['archiveDate' => $val->archive_date]) : Yii::t('app', 'Archive date {archiveDate}', ['archiveDate' => $val->archive_date]);?>
                    </small>
                    <?php }?>
				</div>
			</div>
		</div>
	</div>
    <?php }?>
</div>

<?php 
$getPagination = $dataProvider->getPagination();
$pagination = \themes\stackadmin\components\widgets\LinkPager::widget([
    'pagination' => $dataProvider->getPagination(),
    'options' => [
        'class' => 'pagination mb-0',
    ],
]);

echo ($getPagination->totalCount > $getPagination->pageSize) ? $this->renderWidget($pagination, [
    'overwrite' => true,
    'cards' => Yii::$app->request->isAjax ? false : true,
    'textAlign' => 'center',
]) : '';?>