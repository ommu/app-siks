<?php
$this->context->layout = 'admin_default';
$this->titleShow = false;
?>

<div class="row">
	<div class="col-lg">
		<?php echo \siks\app\components\ArchivePopular::widget([
			'isNewest' => true,
			'ignoreLevel' => [2,3,4,5,6,7],
		]); ?>
	</div>

	<div class="col-lg">
		<?php echo \siks\app\components\ArchivePopular::widget([
			'ignoreLevel' => [2,3,4,5,6,7],
		]); ?>
	</div>
</div>
