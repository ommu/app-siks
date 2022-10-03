<?php
/**
 * @var $this app\components\View
 * @var $this siks\app\controllers\archive\SiteController
 * @var $model ommu\archive\models\Archives
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2020 OMMU (www.ommu.co)
 * @created date 5 March 2020, 12:04 WIB
 * @link https://bitbucket.org/ommu/siks
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use ommu\archive\models\ArchiveLevel;
use themes\stackadmin\assets\ThemePluginAsset;

$themeAsset = ThemePluginAsset::register($this);
\themes\stackadmin\assets\FlatpickrPluginAsset::register($this);
\themes\stackadmin\assets\DaterangepickerPluginAsset::register($this);
$this->registerCssFile($themeAsset->baseUrl . '/demo/css/flatpickr.css', ['depends' => [ThemePluginAsset::className()]]);
$this->registerCssFile($themeAsset->baseUrl . '/demo/css/flatpickr-airbnb.css', ['depends' => [ThemePluginAsset::className()]]);
$this->registerJsFile($themeAsset->baseUrl . '/demo/js/flatpickr.js', ['depends' => [ThemePluginAsset::className()]]);
$this->registerJsFile($themeAsset->baseUrl . '/demo/js/daterangepicker.js', ['depends' => [ThemePluginAsset::className()]]);

$by = Yii::$app->request->get('by');
$title = Yii::$app->request->get('title');
$level = Yii::$app->request->get('level');
$levelId = Yii::$app->request->get('level_id');
if($levelId == null && $level != null) {
    $levelId = $level;
}
$document = Yii::$app->request->get('document');
$creation_date = Yii::$app->request->get('creation_date');
?>

<div class="card card-form">
    <form action="<?php echo Url::to(['index']);?>" class="d-flex flex-column flex-sm-row">
        <div class="card-form__body card-body-form-group flex">
            <div class="row">
                <div class="col-sm-auto">
                    <div class="form-group">
                        <label for="filter_title"><?php echo Yii::t('app', 'Search');?></label>
                        <?php echo $by != null ? Html::input('hidden', 'by', $by) : '';?>
                        <input id="filter_title" type="text" name="title" value="<?php echo $title;?>" class="form-control" placeholder="<?php echo Yii::t('app', 'Enter archive title');?>">
                    </div>
                </div>
                <div class="col-sm-auto">
                    <div class="form-group">
                        <label for="filter_level"><?php echo Yii::t('app', 'Level');?></label>
                        <br/>
                        <?php echo Html::dropDownList('level_id', $levelId ? $levelId : '', ArrayHelper::merge([''=>'Any'], ArchiveLevel::getLevel(1)), ['class'=>'custom-select', 'id'=>'filter_level', 'style'=>'width: 150px']);?>
                    </div>
                </div>
                <div class="col-sm-auto">
                    <div class="form-group">
                        <label for="filter_document"><?php echo Yii::t('app', 'Has Document');?></label>
                        <div class="custom-control custom-checkbox mt-sm-2">
                            <?php echo Html::checkbox('document', $document, ['class'=>'custom-control-input', 'id'=>'filter_document']);?>
                            <?php echo Html::label(Yii::t('app', 'Yes'), 'filter_document', ['class'=>'custom-control-label']);?>
                        </div>
                    </div>
                </div>
                <?php if($isArchive == true) {?>
                <div class="col-sm-auto">
                    <div class="form-group" style="width: 200px;">
                        <label for="filter_date"><?php echo Yii::t('app', 'Creation Date');?></label>
                        <input id="filter_date" type="text" class="form-control flatpickr-input" placeholder="<?php echo Yii::t('app', 'Select creation date ...');?>" name="creation_date" value="<?php echo $creation_date;?>" data-toggle="flatpickr" data-flatpickr-mode="range" data-flatpickr-alt-format="d-m-Y" data-flatpickr-date-format="d-m-Y">
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
        <button class="btn bg-white border-left border-top border-top-sm-0 rounded-top-0 rounded-top-sm rounded-left-sm-0"><i class="material-icons text-primary">refresh</i></button>
    </form>
</div>