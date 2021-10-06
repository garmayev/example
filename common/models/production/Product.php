<?php

namespace uusm\models\production;

use yii\db\ActiveQuery;

/**
 *
 * @property int $id [int(11)]
 * @property string $title [varchar(255)]
 * @property string $summary [varchar(255)]
 * @property string $description
 * @property int $category_id [int(11)]
 *
 * @property Category $category
 */
class Product extends \yii\db\ActiveRecord
{
	public static function tableName(): string
	{
		return "{{%product}}";
	}

	public function rules(): array
	{
		return [
			[["title", "description"], "required"],
			[["title", "summary", "description"], "string"],
			[["category_id"], "exist", "targetClass" => Category::class, "targetAttribute" => "id"],
		];
	}

	public function attributeLabels(): array
	{
		return [
			"title" => \Yii::t("app", "Title"),
			"summary" => \Yii::t("app", "Summary"),
			"description" => \Yii::t("app", "Description"),
			"category_id" => \Yii::t("app", "Category ID"),
		];
	}

	public function getCategory(): ActiveQuery
	{
		return $this->hasOne(Category::class, ["id" => "category_id"]);
	}
}