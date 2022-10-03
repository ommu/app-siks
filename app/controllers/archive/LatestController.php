<?php
/**
 * LatestController
 * @var $this app\components\View
 * @var $this siks\app\controllers\archive\LatestController
 * @var $model ommu\archive\models\Archives
 *
 * Reference start
 * TOC :
 *	Index
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2020 OMMU (www.ommu.co)
 * @created date 4 March 2020, 16:36 WIB
 * @link https://bitbucket.org/ommu/bpadjogja-mediaakses
 *
 */

namespace siks\app\controllers\archive;

use Yii;
use siks\app\controllers\archive\SiteController;

class LatestController extends SiteController
{
    public static $backoffice = false;

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'latest';
    }
}
