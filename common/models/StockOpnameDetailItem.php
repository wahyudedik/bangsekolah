<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "stock_opname_detail_item".
 *
 * @property int $id
 * @property int $stock_opname_detail_id
 * @property int $item_id
 * @property string $code
 * @property int $amount_before
 * @property int $amount_after
 * @property string $status EXIST, NOT_EXIST
 * @property string $scanned_at
 *
 * @property StockOpnameDetail $stockOpnameDetail
 * @property Item $item
 */
class StockOpnameDetailItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stock_opname_detail_item';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                 'class' => 'yii\behaviors\TimestampBehavior',
                 'attributes' => [
                     \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['scanned_at'],
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
            [['stock_opname_detail_id', 'item_id', 'amount_before', 'amount_after'], 'integer'],
            [['scanned_at'], 'safe'],
            [['code', 'status'], 'string', 'max' => 255],
            [['stock_opname_detail_id'], 'exist', 'skipOnError' => true, 'targetClass' => StockOpnameDetail::className(), 'targetAttribute' => ['stock_opname_detail_id' => 'id']],
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
            'stock_opname_detail_id' => 'Stock Opname Detail ID',
            'item_id' => 'Item ID',
            'product_id' => 'Product ID',
            'code' => 'Code',
            'amount_before' => 'Amount Before',
            'amount_after' => 'Amount After',
            'status' => 'Status',
            'scanned_at' => 'Scanned At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockOpnameDetail()
    {
        return $this->hasOne(StockOpnameDetail::className(), ['id' => 'stock_opname_detail_id']);
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

    public function getExistStatus()
    {
        $opnameDetailItem = StockOpnameDetailItem::find()->where(['status' => 'NOT_EXIST', 'stock_opname_detail_id' => $this->stock_opname_detail_id])->all();

        if (count($opnameDetailItem) > 0) {
            return 'NOT_EXIST';
        } else {
            return 'EXIST';
        }
    }
}
