<?php
$this->cards = false;
$this->titleShow = false;
?>

<div class="card-group">
	<?php echo \siks\app\components\ArchiveStatisticLevel::widget([
		'levelId' => 1,
		'materialIcons' => 'dns',
	]); ?>
	<?php echo \siks\app\components\ArchiveStatisticLevel::widget([
		'levelId' => 8,
		'materialIcons' => 'description',
	]); ?>
	<?php echo \siks\app\components\ArchiveStatisticLevel::widget([
		'materialIcons' => 'dvr',
	]); ?>
</div>

<div class="row">
	<div class="col-sm-12 col-md-6">
		<?php echo \siks\app\components\ArchiveLatest::widget([
            'limit' => 3,
			'ignoreLevel' => [2,3,4,5,6,7],
		]); ?>
	</div>

	<div class="col-sm-12 col-md-6">
		<?php echo \siks\app\components\ArchivePopular::widget([
            'limit' => 3,
			'ignoreLevel' => [2,3,4,5,6,7],
		]); ?>
	</div>
</div>

<?php echo \siks\app\components\ArchiveDigital::widget([
	'isPhoto' => true,
]); ?>

<?php echo \siks\app\components\ArchiveDigital::widget([
	'isPhoto' => false,
]); ?>