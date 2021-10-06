<?php

namespace uusm\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 *
 * @property int $id [int(11)]
 * @property string $thumbs [varchar(255)]
 * @property string $full [varchar(255)]
 * @property int $parent_id [int(11)]
 *
 * @property Bridge $parent
 */
class Image extends ActiveRecord
{
	public $file;

	public static function tableName(): string
	{
		return "{{%images}}";
	}

	public function rules(): array
	{
		return [
			[["thumbs", "full"], "required"],
			[["thumbs", "full"], "string"],
		];
	}

	public function getParent(): ActiveQuery
	{
		return $this->hasOne(Bridge::class, ["id" => "parent_id"]);
	}

	public function upload(): bool
	{
		$this->file->saveAs("img/uploads/full/{$this->file->baseName}.{$this->file->extension}");
		$image = \yii\imagine\Image::getImagine()->open(Yii::getAlias("@webroot/img/uploads/full/{$this->file->baseName}.{$this->file->extension}"));
		\yii\imagine\Image::resize($image, 560, 430)->save(Yii::getAlias("@webroot/img/uploads/thumbs/{$this->file->baseName}.{$this->file->extension}"));
		$this->full = "/img/uploads/full/{$this->file->baseName}.{$this->file->extension}";
		$this->thumbs = "/img/uploads/thumbs/{$this->file->baseName}.{$this->file->extension}";
		return $this->save();
	}

	public function delete()
	{
		unlink(Yii::getAlias("@webroot{$this->thumbs}"));
		unlink(Yii::getAlias("@webroot{$this->full}"));
		return parent::delete();
	}
}