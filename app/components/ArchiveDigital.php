<?php
/**
 * ArchiveDigital
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 17 July 2019, 23:21 WIB
 * @link https://bitbucket.org/ommu/bpadjogja-portal
 * 
 */

namespace siks\app\components;

use yii\helpers\Html;
use yii\helpers\Url;
use ommu\archive\models\Archives;
use yii\helpers\ArrayHelper;

class ArchiveDigital extends \yii\base\Widget
{
    use \ommu\traits\FileTrait;

	/**
	 * {@inheritdoc}
	 */
	public $isPhoto = true;
	/**
	 * {@inheritdoc}
	 */
	public $limit = 3;
    /**
     * {@inheritdoc}
     */
    public $archiveContent = [];

    /**
     * {@inheritdoc}
     */
	public function init()
	{
		$setting = \ommu\archive\models\ArchiveSetting::find()
			->select(['reference_code_sikn', 'reference_code_separator', 'short_code', 'image_type', 'document_type', 'maintenance_mode', 'maintenance_image_path', 'maintenance_document_path'])
			->where(['id' => 1])
            ->one();

        $imageFileType = $this->formatFileType($setting->image_type);
        $documentFileType = $this->formatFileType($setting->document_type);

        $limit = $this->limit * 3; 

		$model = Archives::find()
            ->alias('t')
			->select(['t.id', 't.level_id', 't.title', 't.code', 't.archive_date', 't.archive_file', 't.creation_date'])
			->andWhere(['t.publish' => 1])
			->andWhere(['<>', 't.archive_file', '']);
		if($this->isPhoto == true) {
			$model->andWhere(['t.archive_type' => 'photo']);
		} else {
			$model->andWhere(['t.archive_type' => 'text']);
		}
        if(!empty($imageFileType)) {
            $criteria = [];
            foreach ($imageFileType as $key => $val) {
                $criteria[$key] = ['like', 't.archive_file', '%'.$val, false];
            }
            $model->andWhere(ArrayHelper::merge(['or'], $criteria));
        }

		$model = $model->limit($limit)
			->orderBy('t.creation_date DESC, t.id DESC')
			->all();

        if($model != null) {
            foreach ($model as $key => $val) {
                $extension = pathinfo($val->archive_file, PATHINFO_EXTENSION);
                $isDocument = in_array($extension, $documentFileType) ? true : false;

                if($val->isNewFile) {
                    $uploadPath = join('/', [$val::getUploadPath(), $val->id]);
                } else {
                    $uploadPath = join('/', [$val::getUploadPath(), ($isDocument == true ? $setting->maintenance_document_path : $setting->maintenance_image_path)]);
                }
                $fileExists = $val->archive_file != '' && file_exists(join('/', [$uploadPath, $val->archive_file])) ? true : false;

                if(!($val->archive_file && $fileExists)) {
                    continue;
                }

                $title = $val::htmlHardDecode($val->title);
                if($setting->short_code)
                    $code = !$setting->maintenance_mode ? $val->code : $val->confirmCode;
                else {
                    $code = join($setting->reference_code_separator, ArrayHelper::map($val->referenceCode, 'level', 'code'));
                    if($setting->maintenance_mode)
                        $code = join($setting->reference_code_separator, ArrayHelper::map($val->referenceCode, 'level', 'confirmCode'));
                }
                if($setting->reference_code_sikn) {
                    $code = $setting->reference_code_sikn.' '.$code;
                }

                if($val->isNewFile) {
                    $uploadPathFront = join('/', [$val::getUploadPath(false), $val->id]);
                } else {
                    $uploadPathFront = join('/', [$val::getUploadPath(false), ($isDocument == true ? $setting->maintenance_document_path : $setting->maintenance_image_path)]);
                }
                $archiveFilePath = Url::to(join('/', ['@webpublic', $uploadPathFront, $val->archive_file]));

                $this->archiveContent[$key] = [
                    'id' => $val->id,
                    'title' => $title,
                    'levelId' => $val->level_id,
                    'levelName' => $val->levelTitle->message,
                    'code' => $code,
                    'archiveDate' => $val->archive_date,
                    'archiveFilePath' => $archiveFilePath,
                    'creationDate' => $val->creation_date,
                    'views' => $val->grid->view,
                ];
            }
        }
	}

    /**
     * {@inheritdoc}
     */
	public function run()
	{
		return $this->render('archive_digital', [
			'archiveContent' => $this->archiveContent,
		]);
	}
}