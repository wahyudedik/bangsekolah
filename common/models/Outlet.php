<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "outlet".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $address
 * @property string $city
 * @property string $status ACTIVE, NONACTIVE
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Order[] $orders
 * @property UserOutlet[] $userOutlets
 */
class Outlet extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'outlet';
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
            [['address'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'email', 'city', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'address' => 'Address',
            'city' => 'City',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['outlet_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserOutlets()
    {
        return $this->hasMany(UserOutlet::className(), ['outlet_id' => 'id']);
    }

    public function getUserAdmin()
    {
        $admin = 'NOT ASSIGNED';
        foreach ($this->userOutlets as $userOutlet) {
            $admin = $userOutlet->user->name;
        }
        return $admin;
    }
}
