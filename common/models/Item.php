<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "item".
 *
 * @property int $id
 * @property int $purchase_order_id NULL if not purchased
 * @property int $production_id NULL if not produced
 * @property int $product_id
 * @property string $product_code
 * @property string $code
 * @property int $amount
 * @property int $base_amount
 * @property int $price_purchase
 * @property string $expired_date
 * @property string $status ACTIVE, NONACTIVE
 * @property string $created_at
 * @property int $created_by
 *
 * @property PurchaseOrder $purchaseOrder
 * @property Production $production
 * @property Product $product
 * @property OrderDetailItem[] $orderDetailItems
 * @property ProductionItem[] $productionItems
 * @property PurchaseOrderDetailItem[] $purchaseOrderDetailItems
 */
class Item extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item';
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
            [['purchase_order_id', 'production_id', 'product_id', 'amount', 'base_amount', 'price_purchase', 'created_by'], 'integer'],
            [['expired_date', 'created_at'], 'safe'],
            [['code'], 'unique'],
            [['product_code', 'code', 'name', 'unit', 'status'], 'string', 'max' => 255],
            [['purchase_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => PurchaseOrder::className(), 'targetAttribute' => ['purchase_order_id' => 'id']],
            [['production_id'], 'exist', 'skipOnError' => true, 'targetClass' => Production::className(), 'targetAttribute' => ['production_id' => 'id']],
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
            'production_id' => 'Production ID',
            'product_id' => 'Product ID',
            'product_code' => 'Product Code',
            'code' => 'Code',
            'name' => 'Name',
            'unit' => 'Unit',
            'amount' => 'Amount/Weight',
            'base_amount' => 'Initial Amount',
            'price_purchase' => 'Price Purchase',
            'expired_date' => 'Expired Date',
            'status' => 'Status',
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
    public function getProduction()
    {
        return $this->hasOne(Production::className(), ['id' => 'production_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderDetailItems()
    {
        return $this->hasMany(OrderDetailItem::className(), ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductionItems()
    {
        return $this->hasMany(ProductionItem::className(), ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseOrderDetailItems()
    {
        return $this->hasMany(PurchaseOrderDetailItem::className(), ['item_id' => 'id']);
    }

    public function getStockOpnameDetailItems()
    {
        return $this->hasMany(StockOpnameDetailItem::className(), ['item_id' => 'id']);
    }

    public function getCreatedAt()
    {
        return date('d M Y H:i:s', strtotime($this->created_at));
    }

    public function getExpiredDate()
    {
        return date('d M Y', strtotime($this->expired_date));
    }

    public function getPricePurchaseRupiah()
    {
        return Strings::getRupiah($this->price_purchase);
    }

    public function getPriceProductionRupiah()
    {
        return Strings::getRupiah($this->price_purchase);
    }
}
