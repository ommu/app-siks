<?php
/**
 * PopularController
 * @var $this app\components\View
 * @var $this siks\app\controllers\archive\PopularController
 * @var $model ommu\archive\models\Archives
 *
 * Reference start
 * TOC :
 *	Index
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2020 OMMU (www.ommu.id)
 * @created date 4 March 2020, 16:36 WIB
 * @link https://github.com/ommu/app-siks
 *
 */

namespace siks\app\controllers\archive;

use Yii;
use siks\app\controllers\archive\SiteController;

class PopularController extends SiteController
{
    public static $backoffice = false;

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'popular';
    }
}
