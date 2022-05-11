<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "recipe_detail".
 *
 * @property int $id
 * @property int $production_id
 * @property int $product_id
 * @property int $quantity
 * @property string $created_at
 * @property int $created_by
 *
 * @property Product $product
 * @property Recipe $recipe
 */
class ProductionDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'production_detail';
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
            [['production_id', 'product_id', 'amount'], 'required'],
            [['production_id', 'product_id', 'amount', 'created_by'], 'integer'],
            [['created_at'], 'safe'],
            [['unit'], 'string', 'max' => 255],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['production_id'], 'exist', 'skipOnError' => true, 'targetClass' => Production::className(), 'targetAttribute' => ['production_id' => 'id']],
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
            'product_id' => 'Product ID',
            'amount' => 'Amount',
            'unit' => 'Unit',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduction()
    {
        return $this->hasOne(Production::className(), ['id' => 'production_id']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getCompleteStatus()
    {
        $amount = 0;
        $productionItems = ProductionItem::find()->where(['production_id' => $this->production_id, 'product_id' => $this->product_id])->all();
        foreach ($productionItems as $productionItem) {
            $amount += $productionItem->amount;
        }
        
        if ($this->amount == $amount) {
            return 'COMPLETE';
        } else {
            return 'INCOMPLETE';
        }
    }

}
