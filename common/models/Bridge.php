<?php

namespace uusm\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 *
 * @property int $id [int(11)]
 * @property string $title [varchar(128)]
 * @property string $description
 * @property int $title_image [int(11)]
 *
 * @property-read Image[] $images
 * @property int $favorite [int(11)]
 */

class Bridge extends ActiveRecord
{
	public $files;
	public $checkbox;

	public static function tableName(): string
	{
		return "{{%bridges}}";
	}

	public function rules(): array
	{
		return [
			[["title", "description"], "required"],
			[["title", "description"], "string"],
			[["favorite"], "integer"]
		];
	}

	public function getImages(): ActiveQuery
	{
		return $this->hasMany(Image::class, ["parent_id" => "id"]);
	}

	public function load($data, $formName = null): bool
	{
		$scope = $formName === null ? $this->formName() : $formName;
		if ($scope === '' && !empty($data)) {
			$this->setAttributes($data);
		} elseif (isset($data[$scope])) {
			$this->setAttributes($data[$scope]);
		}
//		var_dump($data["Bridge"]["checkbox"]); die;
		if ( $data["Bridge"]["checkbox"] === "1" ) {
			$this->favorite = 1;
		} else {
			$this->favorite = null;
		}
		return true;
	}

	public function upload(): bool
	{
		if ($this->validate()) {
			foreach ($this->files as $file) {
				$image = new Image();
				$image->file = $file;
				$image->parent_id = $this->id;
				$image->upload();
			}
			return true;
		} else {
			return false;
		}
	}
}