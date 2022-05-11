<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_detail_item".
 *
 * @property int $id
 * @property int $order_detail_id
 * @property int $item_id
 * @property int $price_purchase
 * @property int $price_sale
 * @property string $created_at
 * @property int $created_by
 *
 * @property OrderDetail $orderDetail
 * @property Item $item
 */
class OrderDetailItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_detail_item';
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
            [['order_detail_id', 'item_id', 'created_by', 'price_purchase', 'price_sale'], 'integer'],
            [['created_at'], 'safe'],
            [['order_detail_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderDetail::className(), 'targetAttribute' => ['order_detail_id' => 'id']],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_detail_id' => 'Order Detail ID',
            'item_id' => 'Item ID',
            'price_purchase' => 'Price Purchase',
            'price_sale' => 'Price Sale',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderDetail()
    {
        return $this->hasOne(OrderDetail::className(), ['id' => 'order_detail_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }
}
