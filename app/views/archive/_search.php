<?php
/**
 * @var $this app\components\View
 * @var $this siks\app\controllers\archive\SiteController
 * @var $model ommu\archive\models\Archives
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 18 September 2019, 12:04 WIB
 * @link https://bitbucket.org/ommu/siks
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin([
	'method' => 'get',
	'options' => [
		'data-pjax' => 1,
		'class' => 'mb-4',
	],
]); ?>
<div class="col-12">
	<div class="row">
		<?php $title = Yii::$app->request->get('title');
		echo Html::input('text', 'title', $title, ['placeholder'=>$placeholder, 'class'=>'form-control form-control-lg col']); ?>

		<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary col-auto ml-2']); ?>
	</div>
</div>
<?php ActiveForm::end(); ?>