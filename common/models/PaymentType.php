<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "payment_type".
 *
 * @property int $id
 * @property string $name
 * @property string $label
 * @property string $description
 *
 */
class PaymentType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_type';
    }

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
            [['name', 'label'], 'string', 'max' => 255],
            [['description'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Jenis Pembayaran',
            'label' => 'Label',
            'description' => 'Deskripsi',
        ];
    }

    public static function getList()
    {
        return \yii\helpers\ArrayHelper::map(
            PaymentType::find()->asArray()->all(),
            'id', 'label'
        );
    }
}
