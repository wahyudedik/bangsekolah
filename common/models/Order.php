<?php

namespace common\models;

use Yii;
use common\helpers\Strings;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property string $order_number
 * @property string $invoice_number
 * @property string $note
 * @property string $seller_note
 * @property string $status
 * @property string $created_at
 * @property string $finalized_at
 * @property string $paid_at
 * @property int $created_by
 *
 * @property OrderDetail[] $orderDetails
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
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
            [['order_number'], 'unique'],
            [['note', 'seller_note'], 'string'],
            [['created_at', 'finalized_at', 'paid_at', 'ordered_at'], 'safe'],
            [['created_by', 'shipping_cost', 'tax', 'finalized_by'], 'integer'],
            [['order_number', 'invoice_number', 'status', 'shipping_courier', 'tax_description'], 'string', 'max' => 255],
            [['outlet_id'], 'exist', 'skipOnError' => true, 'targetClass' => Outlet::className(), 'targetAttribute' => ['outlet_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_number' => 'Order Number',
            'invoice_number' => 'Invoice Number',
            'outlet_id' => 'Outlet ID',
            'note' => 'Note',
            'seller_note' => 'Seller Note',
            'shipping_cost' => 'Shipping Cost',
            'shipping_courier' => 'Courier',
            'tax' => 'Tax',
            'tax_description' => 'Tax Description',
            'status' => 'Status',
            'created_at' => 'Created At',
            'ordered_at' => 'Ordered At',
            'finalized_at' => 'Finalized At',
            'paid_at' => 'Paid At',
            'created_by' => 'Created By',
            'finalized_by' => 'Finalized By',
            
            'totalRupiah' => 'Total',
            'shippingCostRupiah' => 'Shipping Cost',
            'taxRupiah' => 'Tax',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOutlet()
    {
        return $this->hasOne(Outlet::className(), ['id' => 'outlet_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderDetails()
    {
        return $this->hasMany(OrderDetail::className(), ['order_id' => 'id']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getFinalizedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'finalized_by']);
    }

    public function getTotal()
    { 
        $total = 0;
        foreach($this->orderDetails as $orderDetail) {
            $total += $orderDetail->quantity * $orderDetail->price_sale;
        }
        return $total;
    }

    public function getTotalRupiah()
    {
        return Strings::getRupiah($this->total);
    }

    public function getShippingCostRupiah()
    {
        return Strings::getRupiah($this->shipping_cost);
    }

    public function getTaxRupiah()
    {
        return Strings::getRupiah($this->tax);
    }

    public function getOrderedAt()
    {
        return date('d M Y H:i:s', strtotime($this->ordered_at));
    }

    public function getFinalizedAt()
    {
        return date('d M Y H:i:s', strtotime($this->finalized_at));
    }

    public function getPaidAt()
    {
        return date('d M Y H:i:s', strtotime($this->paid_at));
    }

    public function getCompleteStatus()
    {
        $completeCount = 0;
        foreach ($this->orderDetails as $orderDetail) {
            if ($orderDetail->completeStatus == 'COMPLETE') {
                $completeCount ++;
            }
        }
        if ($completeCount == count($this->orderDetails)) {
            return 'COMPLETE';
        } else {
            return 'INCOMPLETE';
        }
    }

    public function getSummary()
    {
        $summary = $this->total;
        if($this->shipping_cost != null) {
            $summary += $this->shipping_cost;
        }
        if($this->tax != null) {
            $summary += $this->tax;
        }
        return $summary;
    }

    public function getExistStatus()
    {
        $existCount = 0;
        foreach ($this->orderDetails as $orderDetail) {
            if ($orderDetail->existStatus == 'EXIST') {
                $existCount ++;
            }
        }
        if ($existCount == count($this->orderDetails)) {
            return 'EXIST';
        } else {
            return 'NOT_EXIST';
        }
    }
}
