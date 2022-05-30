<?php

namespace common\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "role".
 *
 * @property string $id
 * @property string $name
 * @property string $description
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property RoleMenu[] $roleMenus
 */
class Role extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'role';
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
            [['name'], 'required'],
            [['description'], 'string'],
            [['status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 50],
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
            'description' => 'Description',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoleMenus()
    {
        return $this->hasMany(RoleMenu::className(), ['role_id' => 'id'])
            ->orderBy(['position' => SORT_ASC]);
    }

    public function getLinkMenu()
    {
        return Html::a(count($this->roleMenus).' Menu', ['role-menu/index', 'role' => $this->id]);
    }

    public static function getList()
    {
        $arr = Role::find()->asArray()->all();
        return yii\helpers\ArrayHelper::map($arr, 'id', 'name');
    }

    public static function getRoleSchool()
    {
        $arr = Role::find()->where(['NOT',['id' => 1]])->asArray()->all();
        return yii\helpers\ArrayHelper::map($arr, 'id', 'name');
    }
}
