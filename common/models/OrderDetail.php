<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_detail".
 *
 * @property int $id
 * @property int $order_id
 * @property int $item_id
 * @property int $product_id
 * @property int $price_sale
 * @property int $quantity
 * @property string $status
 * @property string $created_at
 * @property int $created_by
 *
 * @property Order $order
 * @property Item $item
 * @property Product $product
 */
class OrderDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_detail';
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
            [['order_id', 'product_id', 'quantity', 'created_by', 'price_sale'], 'integer'],
            [['created_at'], 'safe'],
            [['status'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
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
            'order_id' => 'Order ID',
            'product_id' => 'Product ID',
            'price_sale' => 'Price Sale',
            'quantity' => 'Quantity',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
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

    public function getOrderDetailItems()
    {
        return $this->hasMany(OrderDetailItem::className(), ['order_detail_id' => 'id']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getCompleteStatus()
    {
        $completeCount = 0;
        foreach ($this->orderDetailItems as $orderDetailItem) {
            if ($orderDetailItem->status == 'PACKED' || $orderDetailItem->status == 'VERIFIED') {
                $completeCount ++;
            }
        }
        if ($completeCount == $this->quantity) {
            return 'COMPLETE';
        } else {
            return 'INCOMPLETE';
        }
    }

    public function getExistStatus()
    {
        if ($this->quantity > $this->product->stock) {
            return 'NOT_EXIST';
        } else {
            return 'EXIST';
        }
    }
}
