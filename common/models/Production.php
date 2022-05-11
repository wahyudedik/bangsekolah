<?php

namespace common\models;

use Yii;
use common\helpers\Strings;

/**
 * This is the model class for table "production".
 *
 * @property int $id
 * @property int $recipe_id
 * @property int $product_id
 * @property int $quantity
 * @property string $note
 * @property string $status COOKING, FINISHED
 * @property string $created_at
 * @property int $created_by
 *
 * @property Recipe $recipe
 * @property Product $product
 * @property ProductionItem[] $productionItems
 */
class Production extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'production';
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
            [['recipe_id', 'product_id', 'quantity', 'created_by'], 'integer'],
            [['production_number'], 'unique'],
            [['note'], 'string'],
            [['created_at'], 'safe'],
            [['status', 'production_number'], 'string', 'max' => 255],
            [['recipe_id'], 'exist', 'skipOnError' => true, 'targetClass' => Recipe::className(), 'targetAttribute' => ['recipe_id' => 'id']],
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
            'production_number' => 'Production Number',
            'recipe_id' => 'Recipe ID',
            'product_id' => 'Product ID',
            'quantity' => 'Output Quantity',
            'note' => 'Note',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            
            'createdAt' => 'Created At',
            'productionCostRupiah' => 'Production Cost',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecipe()
    {
        return $this->hasOne(Recipe::className(), ['id' => 'recipe_id']);
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
    public function getProductionItems()
    {
        return $this->hasMany(ProductionItem::className(), ['production_id' => 'id']);
    }

    public function getItems()
    {
        return $this->hasMany(Item::className(), ['production_id' => 'id']);
    }

    public function getProductionDetails()
    {
        return $this->hasMany(ProductionDetail::className(), ['production_id' => 'id']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getProductionCost()
    {
        $cost = 0;
        foreach ($this->productionItems as $productionItem) {
            $basePrice = ($productionItem->amount/$productionItem->item->base_amount) * $productionItem->item->price_purchase;
            $cost += $basePrice;
        }
        return $cost;
    }

    public function getProductionCostRupiah()
    {
        return Strings::getRupiah($this->productionCost);
    }

    public function getCompleteStatus()
    {
        $completeCount = 0;
        foreach ($this->productionDetails as $productionDetail) {
            if ($productionDetail->completeStatus == 'COMPLETE') {
                $completeCount ++;
            }
        }
        if ($completeCount == count($this->productionDetails)) {
            return 'COMPLETE';
        } else {
            return 'INCOMPLETE';
        }
    }

    public function getCreatedAt()
    {
        return date('d M Y H:i:s', strtotime($this->created_at));
    }
}
