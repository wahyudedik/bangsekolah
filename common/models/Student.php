<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "student".
 *
 * @property int $id
 * @property int $school_id
 * @property string $nis
 * @property string $nisn
 * @property string $name
 * @property string $class
 * @property string $year
 * @property string $status ACTIVE, NONACTIVE
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Invoice[] $invoices
 * @property School $school
 * @property Transaction[] $transactions
 */
class Student extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'student';
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
            [['school_id'], 'integer'],
            [['created_at', 'updated_at', 'file'], 'safe'],
            // [['nisn'], 'unique'],
            [['nis', 'nisn', 'name', 'class', 'gender', 'year', 'status'], 'string', 'max' => 255],
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
            'school_id' => 'School ID',
            'nis' => 'NIS',
            'nisn' => 'NISN',
            'name' => 'Nama',
            'class' => 'Kelas',
            'gender' => 'Jenis Kelamin',
            'year' => 'Tahun Ajaran',
            'status' => 'Status',
            'created_at' => 'Terdaftar Pada',
            'updated_at' => 'Terakhir Update',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoices()
    {
        return $this->hasMany(Invoice::className(), ['student_id' => 'id'])
        ->andOnCondition(['year' => $this->year]);
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
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['student_id' => 'id']);
    }

    public static function getList()
    {
        return \yii\helpers\ArrayHelper::map(
            Student::find()->where(['school_id' => Yii::$app->session->get('school_id'),'status' => 'ACTIVE'])->asArray()->all(),
            'id', function($model) {
                return $model['name'];
            }
        );
    }

    public function getUnpaidInvoiceCount()
    {
        $invoices = Invoice::find()->where([
                'student_id' => $this->id,
                'year' => $this->year,
                'status' => 'UNPAID'
            ])->all();

        return count($invoices);
    }
}
