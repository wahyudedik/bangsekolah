<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "stock_opname".
 *
 * @property int $id
 * @property string $so_number
 * @property int $created_by
 * @property string $created_at
 *
 * @property StockOpnameDetail[] $stockOpnameDetails
 */
class StockOpname extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stock_opname';
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
            [['so_number'], 'unique'],
            [['created_by'], 'integer'],
            [['created_at'], 'safe'],
            [['so_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'so_number' => 'SO Number',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockOpnameDetails()
    {
        return $this->hasMany(StockOpnameDetail::className(), ['stock_opname_id' => 'id']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getProductCount()
    {
        return count($this->stockOpnameDetails);
    }

    public function getCreatedAt()
    {
        return date('d M Y H:i:s', strtotime($this->created_at));
    }

    public function getCompleteStatus()
    {
        $completeCount = 0;
        foreach ($this->stockOpnameDetails as $stockOpnameDetail) {
            if ($stockOpnameDetail->existStatus == 'NOT_EXIST') {
                $completeCount ++;
            }
        }
        if ($completeCount > 0) {
            return 'NOT_EXIST';
        } else {
            return 'EXIST';
        }
    }

}
