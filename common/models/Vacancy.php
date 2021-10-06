<?php

namespace uusm\models;

use Yii;
use yii\db\ActiveRecord;
use yii\imagine\Image;

/**
 *
 * @property int $id [int(11)]
 * @property string $name [varchar(255)]
 * @property string $stage [varchar(255)]
 * @property string $experience [varchar(255)]
 * @property string $education [varchar(255)]
 * @property string $advanced [varchar(255)]
 * @property string $image [varchar(255)]
 * @property string $feedback
 * @property int $status [int(11)]
 */
class Vacancy extends ActiveRecord
{
	const STATUS_OPEN = 0;
	const STATUS_CLOSE = 1;

	public $file;

	public function rules(): array
	{
		return [
			[["name", "experience", "education"], "required"],
			[["name", "stage", "experience", "education", "advanced", "feedback"], "string"],
			[["status"], "default", "value" => self::STATUS_OPEN],
		];
	}

	public function attributeLabels(): array
	{
		return [
			"name" => Yii::t("app", "Name"),
			"stage" => Yii::t("app", "Stage"),
			"experience" => Yii::t("app", "Experience"),
			"education" => Yii::t("app", "Education"),
			"advanced" => Yii::t("app", "Advanced"),
			"feedback" => Yii::t("app", "Feedback")
		];
	}

	public function upload()
	{
		if ( $this->file )
		{
			if ( $this->image ) {
				unlink(Yii::getAlias("@webroot{$this->image}"));
			}
			$this->file->saveAs("img/uploads/full/{$this->file->baseName}.{$this->file->extension}");
			$image = Yii::getAlias("@webroot/img/uploads/full/{$this->file->baseName}.{$this->file->extension}");
			Image::thumbnail($image, 180, 100)->save(Yii::getAlias("@webroot/img/uploads/thumbs/{$this->file->baseName}.{$this->file->extension}"), ["quality" => 80]);
			unlink($image);
			$this->image = "/img/uploads/thumbs/{$this->file->baseName}.{$this->file->extension}";
			return $this->save();
		}
		return true;
	}
}