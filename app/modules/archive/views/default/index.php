<?php
/**
 * @var $this app\components\View
 * @var $this siks\app\modules\archive\controllers\DefaultController
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 20 May 2019, 11:29 WIB
 * @link https://bitbucket.org/ommu/siks
 *
 */

use yii\helpers\Html;
?>

<p>
	This is the view content for action "<?php echo $this->context->action->id ?>".
	The action belongs to the controller "<?php echo get_class($this->context) ?>"
	in the "<?php echo $this->context->module->id ?>" module.
</p>
<p>
	You may customize this page by editing the following file:<br>
	<code><?php echo __FILE__ ?></code>
</p>

<div class="archive-default-index"></div>