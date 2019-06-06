<?php
use yii\helpers\Html;
use yii\helpers\Url;

$count = number_format($model->getArchives(true));
$levelName = $model->level_name_i;
?>

<div class="card card-body text-center">
	<div class="d-flex flex-row align-items-center">
		<div class="card-header__title m-0"> <i class="material-icons icon-muted icon-30pt"><?php echo $icon;?></i> <span class="h5"><?php echo $levelName;?></span></div>
		<div class="text-amount ml-auto"><?php echo Html::a($count, ['/archive/site/index', 'level'=>$model->id], ['class'=>'default', 'title'=>$count.' '.$levelName]);?></div>
	</div>
</div>