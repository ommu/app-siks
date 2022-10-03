<?php
use yii\helpers\Html;
use yii\helpers\Url;

$context = $this->context;
?>

<div class="card card-body text-center">
	<div class="d-flex flex-row align-items-center">
		<div class="card-header__title m-0"> <i class="material-icons icon-muted icon-30pt"><?php echo $icon;?></i> <?php echo $context->levelName;?></div>
		<div class="text-amount ml-auto"><?php echo Html::a($context->count, $context->url, ['class'=>'default', 'title'=>$context->count.' '.$context->levelName]);?></div>
	</div>
</div>