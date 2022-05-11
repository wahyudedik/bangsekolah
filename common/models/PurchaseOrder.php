<?php

namespace common\models;

use Yii;
use common\helpers\Strings;

/**
 * This is the model class for table "purchase_order".
 *
 * @property int $id
 * @property int $supplier_id
 * @property string $po_number
 * @property string $note
 * @property string $status PENDING, SUCCESS
 * @property string $created_at
 * @property int $created_by
 *
 * @property Item[] $items
 * @property Supplier $supplier
 * @property PurchaseOrderDetail[] $purchaseOrderDetails
 */
class PurchaseOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchase_order';
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
            [['supplier_id', 'created_by'], 'integer'],
            [['po_number'], 'unique'],
            [['note'], 'string'],
            [['created_at'], 'safe'],
            [['po_number', 'status'], 'string', 'max' => 255],
            [['supplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => Supplier::className(), 'targetAttribute' => ['supplier_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'supplier_id' => 'Supplier ID',
            'po_number' => 'Po Number',
            'note' => 'Note',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            
            'totalRupiah' => 'Total',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(Item::className(), ['purchase_order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(Supplier::className(), ['id' => 'supplier_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseOrderDetails()
    {
        return $this->hasMany(PurchaseOrderDetail::className(), ['purchase_order_id' => 'id']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getCreatedAt()
    {
        return date('d M Y H:i:s', strtotime($this->created_at));
    }

    public function getTotal()
    { 
        $total = 0;
        foreach($this->purchaseOrderDetails as $purchaseOrderDetail) {
            $total += $purchaseOrderDetail->quantity * $purchaseOrderDetail->price_purchase;
        }
        return $total;
    }

    public function getTotalRupiah()
    {
        return Strings::getRupiah($this->total);
    }
}
