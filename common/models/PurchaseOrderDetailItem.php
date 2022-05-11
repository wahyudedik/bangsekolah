<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "purchase_order_detail_item".
 *
 * @property int $id
 * @property int $purchase_order_detail_id
 * @property int $item_id
 * @property int $product_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Item $item
 * @property PurchaseOrderDetail $purchaseOrderDetail
 * @property Product $product
 */
class PurchaseOrderDetailItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchase_order_detail_item';
    }

    /**
     * Author: AUFA
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                 'class' => 'yii\behaviors\TimestampBehavior',
                 'attributes' => [
                     \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                     \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
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
            [['purchase_order_detail_id', 'item_id', 'product_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_id' => 'id']],
            [['purchase_order_detail_id'], 'exist', 'skipOnError' => true, 'targetClass' => PurchaseOrderDetail::className(), 'targetAttribute' => ['purchase_order_detail_id' => 'id']],
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
            'purchase_order_detail_id' => 'Purchase Order Detail ID',
            'item_id' => 'Item ID',
            'product_id' => 'Product ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
    public function getPurchaseOrderDetail()
    {
        return $this->hasOne(PurchaseOrderDetail::className(), ['id' => 'purchase_order_detail_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
