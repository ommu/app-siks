<?php
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="card">
	<div class="card-header card-header-large bg-white d-flex align-items-center">
		<h4 class="card-header__title flex m-0"><?php echo $this->context->isNewest ? Yii::t('app', 'Newest') : Yii::t('app', 'Pupular'); ?></h4>
	</div>
	<div class="list-group tab-content list-group-flush">
		<div class="tab-pane active show fade" id="activity_all">
			<?php foreach ($model as $val) {?>
			<div class="list-group-item list-group-item-action d-flex align-items-center ">
				<div class="flex">
					<div class="d-flex align-items-middle">
						<strong class="text-15pt mr-1"><?php echo Html::a($val->title, Url::to(['/archive/site/view', 'id'=>$val->id]));?></strong>
					</div>
					<small class="text-muted"><?php echo strtoupper($val->level->level_name_i);?> | <?php echo $val->code;?></small>
				</div>
				<?php echo Html::a('<i class="material-icons icon-muted ml-3">arrow_forward</i>', Url::to(['/archive/site/view', 'id'=>$val->id]));?>
			</div>
			<?php }?>

			<div class="card-footer text-center border-0">
				<?php echo Html::a(Yii::t('app', 'READMORE').' <i class="material-icons icon-muted ml-1">arrow_forward</i>', Url::to(['/archive/site/index', 'order'=>$this->context->isNewest ? 'newest' : 'popular']), ['class'=>'text-muted']);?>
			</div>
		</div>
	</div>
</div>