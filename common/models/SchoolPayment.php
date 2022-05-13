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
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Invoice[] $invoices
 * @property School $school
 * @property PaymentType $paymentType
 * @property Transaction[] $transactions
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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['school_id', 'payment_type_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['number', 'name', 'year', 'status'], 'string', 'max' => 255],
            [['school_id'], 'exist', 'skipOnError' => true, 'targetClass' => School::className(), 'targetAttribute' => ['school_id' => 'id']],
            [['payment_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentType::className(), 'targetAttribute' => ['payment_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'school_id' => 'School ID',
            'payment_type_id' => 'Payment Type ID',
            'number' => 'Number',
            'name' => 'Name',
            'year' => 'Year',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoices()
    {
        return $this->hasMany(Invoice::className(), ['school_payment_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchool()
    {
        return $this->hasOne(School::className(), ['id' => 'school_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentType()
    {
        return $this->hasOne(PaymentType::className(), ['id' => 'payment_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['school_payment_id' => 'id']);
    }
}
