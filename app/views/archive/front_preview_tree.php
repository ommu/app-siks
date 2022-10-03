<?php
/**
 * @var $this app\components\View
 * @var $this siks\app\controllers\archive\SiteController
 * @var $model ommu\archive\models\Archives
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2020 OMMU (www.ommu.co)
 * @created date 5 March 2020, 15:48 WIB
 * @link https://bitbucket.org/ommu/siks
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;

\siks\app\assets\AciTreeAsset::register($this);

$treeDataUrl = Url::to(['data', 'id'=>$model->id]);
$js = <<<JS
	var treeDataUrl = '$treeDataUrl';
	var selectedId = '$model->id';
JS;
$this->registerJs($js, \yii\web\View::POS_HEAD);
?>

<div id="tree" class="aciTree"></div>