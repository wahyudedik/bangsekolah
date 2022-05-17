<?php

namespace common\models;

use Yii;
use common\helpers\Strings;

/**
 * This is the model class for table "transaction".
 *
 * @property int $id
 * @property int $invoice_id
 * @property int $student_id
 * @property int $school_payment_id
 * @property int $amount
 * @property string $date
 * @property string $note
 * @property int $user_id
 *
 */
class Transaction extends \yii\db\ActiveRecord
{
    public $file;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction';
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
            [['invoice_id', 'student_id', 'school_payment_id', 'amount', 'user_id'], 'integer'],
            [['date', 'note', 'payment_method'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getInvoice()
    {
        return $this->hasOne(Invoice::className(), ['id' => 'invoice_id']);
    }

    public function getStudent()
    {
        return $this->hasOne(Student::className(), ['id' => 'student_id']);
    }

    public function getSchoolPayment()
    {
        return $this->hasOne(SchoolPayment::className(), ['id' => 'school_payment_id']);
    }

    public static function getList()
    {
        return \yii\helpers\ArrayHelper::map(
            Transaction::find()->asArray()->all(),
            'id', 'name'
        );
    }

    public function getAmountRupiah() {
        return Strings::getRupiah($this->amount);
    }
}
