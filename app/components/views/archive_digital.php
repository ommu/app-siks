<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Inflector;

$context = $this->context;

if($archiveContent != null) {?>
<div class="mb-3">
    <strong class="text-dark-gray"><?php echo $context->isPhoto ? Yii::t('app', 'Archive Photo') : Yii::t('app', 'Archive Document');?></strong>
</div>
<div class="row">
	<?php 
	$i = 0;
	foreach ($archiveContent as $val) {
        $i++;
        if($i <= $context->limit) {
        $url = Url::to(['/archive/site/preview', 'id'=>$val['id'], 't'=>Inflector::slug($val['title'])]); ?>
	<div class="col-sm-6 col-md-4">
		<div class="card stories-card-popular">
			<img src="<?php echo $val['archiveFilePath'];?>" alt="<?php echo $val['title'];?>" class="card-img">
			<div class="stories-card-popular__content shadow-content">
				<div class="card-body d-flex align-items-center position-absolute text-white">
                    <i class="material-icons mr-1" style="font-size: inherit;">remove_red_eye</i>
                    <small><?php echo $val['views'];?></small>
				</div>
				<div class="stories-card-popular__title card-body">
					<small class="text-muted text-uppercase"><?php echo strtoupper($val['levelName']);?></small>
					<h5 class="card-title m-0">
						<?php echo Html::a($val['code'], $url, ['title'=>$val['title'], 'class'=>'modal-btn']);?>
					</h5>
                    <?php if($val['archiveDate'] && $val['archiveDate'] != '-') {?>
					<small class="text-white text-lowercase d-inline-block mt-1">
                        <i class="material-icons icon-16pt icon-white mr-1">date_range</i>
                        <?php echo $val['levelId'] == 1 ? Yii::t('app', 'Archive year {archiveDate}', ['archiveDate' => $val['archiveDate']]) : Yii::t('app', 'Archive date {archiveDate}', ['archiveDate' => $val['archiveDate']]);?>
                    </small>
                    <?php }?>
				</div>
			</div>
		</div>
	</div>
    <?php }
    }?>
</div>
<?php }?>