<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "stock_opname_detail".
 *
 * @property int $id
 * @property int $stock_opname_id
 * @property int $product_id
 * @property int $stock_product
 * @property int $stock_opname
 * @property string $status PENDING, MATCH, NOT_MATCH
 * @property string $checked_at
 *
 * @property StockOpname $stockOpname
 * @property Product $product
 * @property StockOpnameDetailItem[] $stockOpnameDetailItems
 */
class StockOpnameDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stock_opname_detail';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                 'class' => 'yii\behaviors\TimestampBehavior',
                 'attributes' => [
                     \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['checked_at'],
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
            [['stock_opname_id', 'product_id', 'stock_product', 'stock_opname'], 'integer'],
            [['checked_at'], 'safe'],
            [['status'], 'string', 'max' => 255],
            [['stock_opname_id'], 'exist', 'skipOnError' => true, 'targetClass' => StockOpname::className(), 'targetAttribute' => ['stock_opname_id' => 'id']],
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
            'stock_opname_id' => 'Stock Opname ID',
            'product_id' => 'Product ID',
            'stock_product' => 'Stock Product',
            'stock_opname' => 'Stock Opname',
            'status' => 'Status',
            'checked_at' => 'Checked At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockOpname()
    {
        return $this->hasOne(StockOpname::className(), ['id' => 'stock_opname_id']);
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
    public function getStockOpnameDetailItems()
    {
        return $this->hasMany(StockOpnameDetailItem::className(), ['stock_opname_detail_id' => 'id']);
    }

    public function getStockOpnameDeletedItems()
    {
        return $this->hasMany(StockOpnameDeletedItem::className(), ['stock_opname_detail_id' => 'id']);
    }

    public function getExistStatus()
    {
        $notExistCount = 0;
        foreach ($this->stockOpnameDetailItems as $stockOpnameDetailItem) {
            if ($stockOpnameDetailItem->existStatus == 'NOT_EXIST') {
                $notExistCount ++;
            }
        }
        if ($notExistCount > 0) {
            return 'NOT_EXIST';
        } else {
            return 'EXIST';
        }
    }
}
