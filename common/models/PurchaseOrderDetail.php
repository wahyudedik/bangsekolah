<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "purchase_order_detail".
 *
 * @property int $id
 * @property int $purchase_order_id
 * @property int $product_id
 * @property int $quantity
 * @property int $price_purchase
 * @property string $created_at
 * @property int $created_by
 *
 * @property PurchaseOrder $purchaseOrder
 * @property Product $product
 * @property PurchaseOrderDetailItem[] $purchaseOrderDetailItems
 */
class PurchaseOrderDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchase_order_detail';
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
            [['purchase_order_id', 'product_id', 'quantity', 'amount', 'price_purchase', 'created_by'], 'integer'],
            [['created_at'], 'safe'],
            [['name', 'unit'], 'string', 'max' => 255],
            [['purchase_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => PurchaseOrder::className(), 'targetAttribute' => ['purchase_order_id' => 'id']],
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
            'purchase_order_id' => 'Purchase Order ID',
            'product_id' => 'Product ID',
            'name' => 'Item Name',
            'quantity' => 'Quantity',
            'amount' => 'Amount',
            'unit' => 'Unit',
            'price_purchase' => 'Price Purchase',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseOrder()
    {
        return $this->hasOne(PurchaseOrder::className(), ['id' => 'purchase_order_id']);
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
    public function getPurchaseOrderDetailItems()
    {
        return $this->hasMany(PurchaseOrderDetailItem::className(), ['purchase_order_detail_id' => 'id']);
    }
}
