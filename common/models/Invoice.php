<?php

namespace common\models;

use Yii;
use common\helpers\Strings;

/**
 * This is the model class for table "invoice".
 *
 * @property int $id
 * @property int $school_id
 * @property int $student_id
 * @property int $school_payment_id
 * @property string $number
 * @property string $year
 * @property string $month
 * @property string $description
 * @property int $amount
 * @property int $paid_amount
 * @property string $due_date
 * @property string $status PAID, UNPAID
 * @property int $user_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Student $student
 * @property User $user
 * @property SchoolPayment $schoolPayment
 * @property School $school
 */
class Invoice extends \yii\db\ActiveRecord
{
    public $payment_type;
    public $class;
    // public $tahun_ajaran;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoice';
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
            [['school_id', 'student_id', 'school_payment_id', 'amount', 'paid_amount', 'user_id'], 'integer'],
            [['description'], 'string'],
            [['date', 'due_date', 'created_at', 'updated_at', 'payment_type', 'class'], 'safe'],
            [['number', 'year', 'month', 'status'], 'string', 'max' => 255],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => Student::className(), 'targetAttribute' => ['student_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['school_payment_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchoolPayment::className(), 'targetAttribute' => ['school_payment_id' => 'id']],
            [['school_id'], 'exist', 'skipOnError' => true, 'targetClass' => School::className(), 'targetAttribute' => ['school_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'school_id' => 'Sekolah',
            'student_id' => 'Siswa',
            'school_payment_id' => 'Pembayaran',
            'number' => 'Nomor',
            'year' => 'Tahun Ajaran',
            'month' => 'Bulan',
            'description' => 'Deskripsi',
            'amount' => 'Jumlah',
            'paid_amount' => 'Jumlah Bayar',
            'date' => 'Tanggal Tagihan',
            'due_date' => 'Jatuh Tempo',
            'status' => 'Status',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            
            'statusLabel' => 'Status',
            'amountRupiah' => 'Jumlah',
            'paidAmountRupiah' => 'Jumlah Bayar',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(Student::className(), ['id' => 'student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolPayment()
    {
        return $this->hasOne(SchoolPayment::className(), ['id' => 'school_payment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchool()
    {
        return $this->hasOne(School::className(), ['id' => 'school_id']);
    }

    public function getAmountRupiah() {
        return Strings::getRupiah($this->amount);
    }

    public function getPaidAmountRupiah() {
        return Strings::getRupiah($this->paid_amount);
    }

    public function getStatusLabel()
    {
        if ($this->status == 'PAID') {
            return '<span class="text-success">Lunas</span>';
        } else {
            if ($this->paid_amount > 0) {
                return '<span class="text-warning">Sebagian</span>';
            } else {
                return '<span class="text-danger">Belum Dibayar</span>';
            }
        }
    }
}
