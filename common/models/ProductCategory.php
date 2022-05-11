<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_category".
 *
 * @property int $id
 * @property string $name
 * @property string $status ACTIVE, NONACTIVE
 *
 * @property Product[] $products
 */
class ProductCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Category',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['product_category_id' => 'id'])
            ->andOnCondition(['status' => 'ACTIVE']);
    }

    public static function getList()
    {
        return \yii\helpers\ArrayHelper::map(
            ProductCategory::find()->where(['status' => 'ACTIVE'])->asArray()->all(),
            'id', 'name'
        );
    }
}
