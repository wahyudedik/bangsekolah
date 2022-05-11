<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "midtrans_notification".
 *
 * @property int $id
 * @property int $peserta_id
 * @property string $order_id
 * @property string $transaction_id
 * @property string $status_code
 * @property string $status_message
 * @property string $gross_amount
 * @property string $payment_type
 * @property string $transaction_time
 * @property string $transaction_status
 * @property string $approval_code
 * @property string $signature_key
 * @property string $bank
 * @property string $va_number
 * @property string $payment_code
 * @property string $store
 * @property string $fraud_status
 * @property string $eci
 * @property string $masked_card
 * @property string $full_text
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Peserta $peserta
 */
class MidtransNotification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'midtrans_notification';
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
            // [['peserta_id', 'order_id', 'gross_amount'], 'required'],
            [['peserta_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['order_id', 'transaction_id', 'status_code', 'status_message', 'gross_amount', 'payment_type', 'transaction_time', 'transaction_status', 'approval_code', 'signature_key', 'bank', 'va_number', 'payment_code', 'store', 'fraud_status', 'eci', 'masked_card'], 'string', 'max' => 255],
            [['full_text'], 'string'],
            [['peserta_id'], 'exist', 'skipOnError' => true, 'targetClass' => Peserta::className(), 'targetAttribute' => ['peserta_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'peserta_id' => 'Peserta ID',
            'order_id' => 'Order ID',
            'transaction_id' => 'Transaction ID',
            'status_code' => 'Status Code',
            'status_message' => 'Status Message',
            'gross_amount' => 'Gross Amount',
            'payment_type' => 'Payment Type',
            'transaction_time' => 'Transaction Time',
            'transaction_status' => 'Transaction Status',
            'approval_code' => 'Approval Code',
            'signature_key' => 'Signature Key',
            'bank' => 'Bank',
            'va_number' => 'Va Number',
            'payment_code' => 'Payment Code',
            'store' => 'Store',
            'fraud_status' => 'Fraud Status',
            'eci' => 'Eci',
            'masked_card' => 'Masked Card',
            'full_text' => 'Full Text',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeserta()
    {
        return $this->hasOne(Peserta::className(), ['id' => 'peserta_id']);
    }
}
