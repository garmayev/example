<?php

namespace uusm\models;

use yii\web\UploadedFile;

/**
 *
 * @property int $id [int(11)]
 *
 * @property string $name [varchar(128)]
 * @property string $phone [varchar(18)]
 * @property string $file_link [varchar(256)]
 * @property int $status [int(11)]
 * @property string $type [varchar(128)]
 * @property string $comment [text]
 * @property string $message
 * @property string $email [varchar(255)]
 */
class Question extends \yii\db\ActiveRecord
{
	public $file;

	const STATUS_OPEN = 0;
	const STATUS_CLOSE = 1;

	const TYPE_CALC = 'calc';
	const TYPE_QUESTION = 'question';

	public static function tableName(): string
	{
		return "{{%question}}";
	}

	public function rules(): array
	{
		return [
			[["name", "phone"], "required"],
			[["name", "phone", "comment", "file_link", "type"], "string"],
			[["status"], "integer"],
			[["name", "phone"], "trim"],
			[['phone'], 'filter', 'filter' => function ($value) {
				return $this->phoneNormalize($value);
			}],
			[["type"], "filter", 'filter' => function ($value) {
				return ($value == self::TYPE_CALC) ? self::TYPE_CALC : self::TYPE_QUESTION;
			}],
			[['status'], 'default', 'value' => self::STATUS_OPEN],
			[['type'], 'default', 'value' => self::TYPE_CALC],
		];
	}

	protected function phoneNormalize($phone)
	{
		$phone = trim($phone);
		$result = str_replace(['(', ')', ' ', '+'], '', $phone);
		if ( strlen($result) === 11 || strlen($result) === 6 ) {
			return $result;
		}
		return null;
	}

	public function upload()
	{
		if ($this->validate()) {
			\Yii::error($this->attributes);
			if ( isset($this->file) ) {
				$link = "files/{$this->file->baseName}.{$this->file->extension}";
				$this->file->saveAs($link);
				$this->file_link = $link;
			}
			return true;
		}
		return false;
	}
}