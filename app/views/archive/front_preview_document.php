<?php
/**
 * @var $this app\components\View
 * @var $this siks\app\controllers\archive\SiteController
 * @var $model ommu\archive\models\Archives
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2020 OMMU (www.ommu.id)
 * @created date 5 March 2020, 15:06 WIB
 * @link https://bitbucket.org/ommu/siks
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;

$setting = $model->getSetting(['image_type', 'document_type', 'maintenance_image_path', 'maintenance_document_path']);
$imageFileType = $model->formatFileType($setting->image_type);
$documentFileType = $model->formatFileType($setting->document_type);
?>

<?php
$extension = pathinfo($model->archive_file, PATHINFO_EXTENSION);
$isDocument = in_array($extension, $documentFileType) ? true : false;

$archiveFile = '';
if($model->archive_file != '') {
    $archiveFile = str_replace('\\', '/', $model->archive_file);
}

if($model->isNewFile) {
    $uploadPath = join('/', [$model::getUploadPath(), $model->id]);
} else {
    $uploadPath = join('/', [$model::getUploadPath(), ($isDocument == true ? $setting->maintenance_document_path : $setting->maintenance_image_path)]);
}
$fileExists = $archiveFile != '' && file_exists(join('/', [$uploadPath, $archiveFile])) ? true : false;

if($archiveFile && $fileExists) {
    if($model->isNewFile) {
        $uploadPath = join('/', [$model::getUploadPath(false), $model->id]);
    } else {
        $uploadPath = join('/', [$model::getUploadPath(false), ($isDocument == true ? $setting->maintenance_document_path : $setting->maintenance_image_path)]);
    }
    $filePath = Url::to(join('/', ['@webpublic', $uploadPath, $archiveFile]));

    if($isDocument == true) {
        echo \app\components\widgets\PreviewPDF::widget([
            'url' => $filePath,
            'navigationOptions' => ['class'=>'summary mb-4'],
            'previewOptions' => ['class'=>'preview-pdf border border-width-3'],
        ]);

    } else {
        echo Html::img($filePath, ['alt'=>$archiveFile, 'class'=>'d-block mb-3', 'style'=>'max-width: 100%;']).($archiveFile ? Html::tag('p', Yii::t('app', 'File: {archive_file}', ['archive_file'=>$archiveFile]), ['class' => 'mb-0']) : '');
    }

} else {?>
	<div class="bs-example" data-example-id="simple-jumbotron">
		<div class="jumbotron mb-0">
			<h1><?php echo $archiveFile ? Yii::t('app', 'Archive document not found') : Yii::t('app', 'Archive document not available');?></h1>
			<?php echo $archiveFile ? Html::tag('p', Yii::t('app', 'File: {archive_file}', ['archive_file'=>$archiveFile])) : '';?>
		</div>
	</div>
<?php }?>