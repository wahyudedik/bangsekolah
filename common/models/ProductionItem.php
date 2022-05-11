<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "production_item".
 *
 * @property int $id
 * @property int $production_id
 * @property int $item_id
 * @property int $product_id
 * @property int $quantity
 * @property string $created_at
 * @property int $created_by
 *
 * @property Production $production
 * @property Item $item
 * @property Product $product
 */
class ProductionItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'production_item';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                 'class' => 'yii\behaviors\TimestampBehavior',
                 'attributes' => [
                     \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                 ],
                 'value' => new \yii\db\Expression('NOW()'),
             ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['production_id', 'item_id', 'product_id', 'amount', 'created_by'], 'integer'],
            [['created_at'], 'safe'],
            [['unit'], 'string', 'max' => 255],
            [['production_id'], 'exist', 'skipOnError' => true, 'targetClass' => Production::className(), 'targetAttribute' => ['production_id' => 'id']],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'production_id' => 'Production ID',
            'item_id' => 'Item ID',
            'product_id' => 'Product ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduction()
    {
        return $this->hasOne(Production::className(), ['id' => 'production_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
