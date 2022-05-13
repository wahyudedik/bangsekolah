<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "school_payment".
 *
 * @property int $id
 * @property int $school_id
 * @property int $payment_type_id
 * @property string $number
 * @property string $name
 * @property string $year
 * @property string $status ACTIVE, NONACTIVE
 *
 */
class SchoolPayment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'school_payment';
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
            [['school_id', 'payment_type_id'], 'integer'],
            [['name', 'year', 'number', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => 'Nomor (Kode Pembayaran)',
            'name' => 'Nama Pembayaran',
            'year' => 'Tahun Ajaran',
            'status' => 'Status',
            'payment_type_id' => 'Jenis Pembayaran',
        ];
    }

    public function getSchool()
    {
        return $this->hasOne(School::className(), ['id' => 'school_id']);
    }

    public function getPaymentType()
    {
        return $this->hasOne(PaymentType::className(), ['id' => 'payment_type_id']);
    }

    public static function getList()
    {
        return \yii\helpers\ArrayHelper::map(
            SchoolPayment::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE'])->asArray()->all(),
            'id', 'name'
        );
    }
}
