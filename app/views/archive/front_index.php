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
use yii\helpers\Inflector;
?>

<?php echo $this->render('_filter', [
    'isArchive' => $isArchive,
]);?>

<?php if(!empty($models)) {
    foreach ($models as $key => $model) {?>
        <p class="text-dark-gray d-flex align-items-center mt-3">
            <i class="material-icons icon-muted mr-2">event</i>
            <strong><?php echo Yii::$app->formatter->asDate($key, 'php:D');?>, <?php echo Yii::$app->formatter->asDate($key, 'long');?></strong>
        </p>
        <?php foreach ($model as $key => $val) {
            $title = $this::htmlHardDecode($val['title']);?>
            <div class="row align-items-center projects-item mb-1">
                <div class="col-sm">
                    <div class="card m-0">
                        <div class="px-4 py-3">
                            <div class="row align-items-center">
                                <div class="col" style="min-width: 300px">
                                    <div class="">
                                        <a href="<?php echo Url::to(['/archive/site/view', 'id'=>$val['id'], 't'=>Inflector::slug($title)]);?>" class="text-body" title="<?php echo $title;?>"><strong class="text-15pt mr-2"><?php echo $title;?></strong></a>
                                        <a href="<?php echo Url::to(['/archive/site/index', 'level'=>$val['levelId'], 't'=>Inflector::slug($val['levelName'])]);?>" class="badge text-uppercase badge-success"><?php echo ucwords($val['levelName']);?></a>
                                    </div>
                                    <div class="text-muted mt-1">
                                        <small class="d-flex align-items-center">
                                            <i class="material-icons icon-16pt icon-muted mr-1">label_outline</i>
                                            <?php echo $val['code'];?>
                                        </small>
                                        <?php if($val['archiveDate'] && $val['archiveDate'] != '-') {?>
                                        <small class="d-flex align-items-center">
                                            <i class="material-icons icon-16pt icon-muted mr-1">date_range</i>
                                            <?php echo $val['levelId'] == 1 ? Yii::t('app', 'Archive year {archiveDate}', ['archiveDate' => $val['archiveDate']]) : Yii::t('app', 'Archive date {archiveDate}', ['archiveDate' => $val['archiveDate']]);?>
                                        </small>
                                        <?php }?>
                                        <small class="d-flex align-items-center">
                                            <i class="material-icons icon-16pt icon-muted mr-1">visibility</i>
                                            <?php echo Yii::t('app', '{views} views', ['views' => $val['views']]);?>
                                        </small>
                                    </div>
                                </div>
                                <?php if($val['archiveFile'] && $val['archiveFile'] != '-') {?>
                                <div class="col-auto d-flex align-items-center" style="min-width: 20px;">
                                    <a href="<?php echo Url::to(['/archive/site/preview', 'id'=>$val['id'], 't'=>Inflector::slug($title)]);?>" class="modal-btn" title="<?php echo Yii::t('app', 'See archived documents');?>">
                                        <i class="material-icons icon-muted icon-20pt ml-2">attachment</i>
                                    </a>
                                </div>
                                <?php }?>
                                <div class="col-auto d-flex align-items-center" style="min-width: 20px;">
                                    <a href="<?php echo Url::to(['/archive/site/tree', 'id'=>$val['id'], 't'=>Inflector::slug($title)]);?>" class="modal-btn" title="<?php echo Yii::t('app', 'See archived tree');?>">
                                        <i class="material-icons icon-muted icon-20pt ml-2">layers</i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<?php   }
    }
    
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
    ]) : '';

} else {?>
    <div class="alert alert-soft-secondary d-flex align-items-center" role="alert">
        <i class="material-icons mr-3">error_outline</i>
        <div class="text-body">
            <?php echo Yii::t('app', 'Archive not found');?>
        </div>
    </div>
<?php }?>