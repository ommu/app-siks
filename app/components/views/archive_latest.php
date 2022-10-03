<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Inflector;
?>

<div class="card">
	<div class="card-header card-header-large bg-white d-flex align-items-center">
		<h4 class="card-header__title flex m-0"><?php echo Yii::t('app', 'Latests'); ?></h4>
	</div>
    <div class="card-header card-header-tabs-basic nav" role="tablist">
        <?php $i = 0;
        foreach ($orderBy as $key => $val) {
            $i++; ?>
        <a href="#archive-latest-<?php echo $val;?>" <?php echo $i == 1 ? 'class="active"' : '';?> title="<?php echo ucwords($val);?>" data-toggle="tab" role="tab"><?php echo ucwords($val);?></a>
        <?php }?>
    </div>
    <div class="list-group tab-content">
        <?php $i = 0;
        foreach ($orderBy as $key => $val) {
            $i++; ?>
        <div class="tab-pane fade <?php echo $i == 1 ? 'active show' : '';?>" id="archive-latest-<?php echo $val;?>">
            <?php $model = $archiveContent[$val];
            if(!empty($model)) {
                foreach ($model as $key => $row) {
                    $url = Url::to(['/archive/site/view', 'id'=>$row['id'], 't'=>Inflector::slug($row['title'])]);?>
            <div class="list-group-item list-group-item-action d-flex align-items-center ">
                <div class="flex">
                    <div class="d-flex align-items-middle mb-1">
                        <strong class="text-15pt mr-1"><?php echo Html::a($row['title'], $url, ['class'=>'default', 'title'=>$row['title']]);?></strong>
                    </div>
                    <div class="text-muted">
                        <small class="d-flex align-items-top">
                            <i class="material-icons icon-16pt icon-muted mr-1">label_outline</i>
                            <?php echo ucwords($row['levelName']);?> <?php echo $row['code'];?>
                        </small>
                        <?php if($row['archiveDate'] && $row['archiveDate'] != '-') {?>
                        <small class="d-flex align-items-top">
                            <i class="material-icons icon-16pt icon-muted mr-1">date_range</i>
                            <?php echo $row['levelId'] == 1 ? Yii::t('app', 'Archive year {archiveDate}', ['archiveDate' => $row['archiveDate']]) : Yii::t('app', 'Archive date {archiveDate}', ['archiveDate' => $row['archiveDate']]);?>
                        </small>
                        <?php }?>
                        <small class="d-flex align-items-top">
                            <i class="material-icons icon-16pt icon-muted mr-1">date_range</i>
                            <?php echo Yii::t('app', 'Created {creationDate}', ['creationDate' => Yii::$app->formatter->asDate($row['creationDate'], 'long')]);?>
                        </small>
                    </div>
                </div>
                <?php echo Html::a('<i class="material-icons icon-muted ml-3">arrow_forward</i>', $url);?>
            </div>

                <?php }
            } else {?>
            <div class="alert alert-soft-secondary d-flex align-items-center m-3" role="alert">
                <i class="material-icons mr-3">error_outline</i>
                <div class="text-body">
                    <?php if($val == 'year') {
                        echo Yii::t('app', 'No recent archive in the last year');
                    } else if($val == 'month') {
                        echo Yii::t('app', 'No recent archives in the last month');
                    } else if($val == 'week') {
                        echo Yii::t('app', 'No recent archives in the last week');
                    } else if($val == 'yesterday') {
                        echo Yii::t('app', 'No recent archives yesterday');
                    } else if($val == 'today') {
                        echo Yii::t('app', 'No recent archives on this day');
                    }?>
                </div>
            </div>
            <?php }?>

			<div class="card-footer text-center border-top">
				<?php echo Html::a(Yii::t('app', 'Readmore').' <i class="material-icons icon-muted ml-1">arrow_forward</i>', ['/archive/latest/index'], ['class'=>'text-muted', 'title'=>Yii::t('app', 'Readmore')]);?>
			</div>
        </div>
        <?php }?>
    </div>
</div>