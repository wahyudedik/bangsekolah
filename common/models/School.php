<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "school".
 *
 * @property int $id
 * @property string $name
 * @property string $status ACTIVE, NONACTIVE
 * @property string $created_at
 * @property string $updated_at
 *
 * @property SchoolClass[] $schoolClasses
 * @property SchoolMonth[] $schoolMonths
 * @property SchoolPayment[] $schoolPayments
 * @property Student[] $students
 * @property UserSchool[] $userSchools
 */
class School extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'school';
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
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'status', 'address', 'city', 'phone', 'image'], 'string', 'max' => 255],
            [['invoice_number', 'transaction_number'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Nama Sekolah',
            'address' => 'Alamat',
            'city' => 'Kota',
            'phone' => 'Telepon',
            'image' => 'Logo',
            'status' => 'Status',
            'created_at' => 'Tanggal Daftar',
            'updated_at' => 'Terakhir Diupdate',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolClasses()
    {
        return $this->hasMany(SchoolClass::className(), ['school_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolMonths()
    {
        return $this->hasMany(SchoolMonth::className(), ['school_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolPayments()
    {
        return $this->hasMany(SchoolPayment::className(), ['school_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudents()
    {
        return $this->hasMany(Student::className(), ['school_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSchools()
    {
        return $this->hasMany(UserSchool::className(), ['school_id' => 'id']);
    }
}
