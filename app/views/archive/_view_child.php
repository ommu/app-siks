<?php
/**
 * @var $this app\components\View
 * @var $this siks\app\controllers\archive\SiteController
 * @var $model ommu\archive\models\Archives
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 16 September 2019, 21:46 WIB
 * @link https://bitbucket.org/ommu/siks
 *
 */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

$title = $model::htmlHardDecode($model->title);
?>

<div class="mr-auto">
	<?php echo Html::a($model->title, ['view', 'id'=>$model->id, 't'=>Inflector::slug($title)], ['title'=>$title, 'class'=>'d-block mb-1', 'data-pjax'=>0]);?>
	<span class="small"><?php echo $model::parseCode($model);?></span>
	<?php if(count($medium = $model->getChilds(['sublevel'=>false, 'back3nd'=>false])) != 0) {?>
	<span class="small">/ <?php echo $model::parseChilds($medium, null, ', ');?></span>
	<?php }
	if($model->archive_file) {?>
	<i class="material-icons icon-muted icon-20pt ml-2">attachment</i>
	<?php }?>
</div>
<div class="d-flex align-items-center ml-5">
	<span class="badge badge-success text-uppercase"><?php echo $model->level->level_name_i;?></span>
</div>