<?php
/**
 * @var $this app\components\View
 * @var $this siks\app\controllers\archive\SiteController
 * @var $model ommu\archive\models\Archives
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 16 September 2019, 21:46 WIB
 * @link https://bitbucket.org/ommu/siks
 *
 */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

$title = $model::htmlHardDecode($model->title);
?>

<div class="col-sm-1 mb-1 mb-sm-0">
	<div class="text-dark-gray text-uppercase"><?php echo $model->level->level_name_i;?></div>
</div>
<div class="col-sm">
	<div class="card m-0">
		<div class="px-4 py-3">
			<div class="row align-items-center">
				<div class="col" style="min-width: 300px">
					<div class="d-flex align-items-center">
						<?php echo Html::a($title, ['view', 'id'=>$model->id, 't'=>Inflector::slug($title)], ['title'=>$title, 'class'=>'text-body text-15pt mr-2 font-weight-bold']);?>
					</div>
					<div class="d-flex align-items-center mt-1">
						<small class="text-dark-gray mr-2"><?php echo $model::parseCode($model, ['link'=>true]);?></small>
					</div>
				</div>
				<div class="col-auto align-items-center text-right" style="min-width: 140px;">
					<?php if($model->archive_date) {?>
					<span class="text-dark-gray"><?php echo strtolower($model->level->level_name_i) != 'fond' ? Yii::$app->formatter->asDate($model->archive_date, 'long') : $model->archive_date;?></span>
					<?php }
					
					if($model->archive_file) {?>
					<i class="material-icons icon-muted icon-20pt ml-2">attachment</i>
					<?php }?>
				</div>
			</div>
		</div>
	</div>
</div>