<?php

namespace uusm\models\production;

use Yii;
use yii\imagine\Image;
use yii\web\UploadedFile;
use function Webmozart\Assert\Tests\StaticAnalysis\null;

/**
 *
 * @property int $id [int(11)]
 * @property string $title
 * @property string $summary [varchar(255)]
 * @property string $description
 * @property int $favorite [int(11)]
 * @property string $image [varchar(255)]
 */
class Category extends \yii\db\ActiveRecord
{
	public $file = null;

	public static function tableName(): string
	{
		return "{{%category}}";
	}

	public function rules(): array
	{
		return [
			[["title", "summary"], "required"],
			[["title", "summary", "description"], "string"],
			[["favorite"], "integer"],
			[["favorite"], "default", "value" => 0],
			[["image"], "filter", "filter" => "addslashes"]
//			[["file"], "file", "skipOnEmpty" => true],
		];
	}

	public function attributeLabels(): array
	{
		return [
			"title" => Yii::t("app", "Title"),
			"summary" => Yii::t("app", "Summary"),
			"description" => Yii::t("app", "Description"),
			"favorite" => Yii::t("app", "Favorite"),
		];
	}

	public function upload(): bool
	{
		if ( !$this->isNewRecord ) {
			unlink(Yii::getAlias("@webroot{$this->image}"));
		}
		if ($this->validate()) {
			$this->file->saveAs("img/uploads/full/{$this->file->baseName}.{$this->file->extension}");
			$image = Image::getImagine()->open(Yii::getAlias("@webroot/img/uploads/full/{$this->file->baseName}.{$this->file->extension}"));
			Image::resize($image, 560, 430)->save(Yii::getAlias("@webroot/img/uploads/thumbs/{$this->file->baseName}.{$this->file->extension}"));
			$this->image = "/img/uploads/thumbs/{$this->file->baseName}.{$this->file->extension}";
			unlink(Yii::getAlias("@webroot/img/uploads/full/{$this->file->baseName}.{$this->file->extension}"));
			return true;
		} else {
			return false;
		}
	}
}