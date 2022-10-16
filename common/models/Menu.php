<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $controller
 * @property string $actions
 * @property string $default_action
 * @property string $icon
 * @property int $parent_id
 * @property int $visibility
 * @property string $created_at
 * @property string $updated_at
 */
class Menu extends \yii\db\ActiveRecord
{

    public $json_action;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'menu';
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
            [['parent_id', 'visibility'], 'integer'],
            [['created_at', 'updated_at', 'json_action'], 'safe'],
            [['name', 'icon'], 'string', 'max' => 50],
            [['controller', 'default_action'], 'string', 'max' => 255],
            [['actions'], 'string'],
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
            'controller' => 'Controller',
            'actions' => 'Actions',
            'icon' => 'Icon',
            'parent_id' => 'Parent Menu',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            
            'actionRaw' => 'Actions',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoleMenus()
    {
        return $this->hasMany(RoleMenu::className(), ['menu_id' => 'id']);
    }

    public function getParent()
    {
        return $this->hasOne(Menu::className(), ['id' => 'parent_id']);
    }

    public function getChilds()
    {
        return $this->hasMany(Menu::className(), ['parent_id' => 'id']);
    }

    public static function getParentList()
    {
        $arr = Menu::find()->where(['level' => 1])->asArray()->all();
        return yii\helpers\ArrayHelper::map($arr, 'id', 'name');
    }

    public static function getList()
    {
        $arr = Menu::find()->asArray()->all();
        return yii\helpers\ArrayHelper::map($arr, 'id', 'name');
    }

    public function getIconRaw()
    {
        return '<i class="'.$this->icon.'" style="font-size:16px;"></i>';
    }

    public function getActionRaw()
    {
        $string = '';
        $json_action = json_decode($this->actions);
        if ($json_action) {
            foreach ($json_action as $act) {
                $string .= '<span class="badge badge-success">'.$act.'</span>';
            }
        }
        return $string;
    }

    public function getVisibilityLabel()
    {
        if ($this->visibility === 1) {
            return '<label class="label label-success">Show</label>';
        } else {
            return '<label class="label label-default" style="color:white !important;">Hide</label>';
        }
    }

    public static function getListTree()
    {
        $arr = [];
        $parents = Menu::find()->where(['parent_id' => null])->all();
        foreach ($parents as $parent) {
            $arr[] = [
                'id' => $parent->id,
                'name' => $parent->name,
            ];
            if ($parent->childs) {            
                foreach ($parent->childs as $child) {
                    $arr[] = [
                        'id' => $child->id,
                        'name' => '--- '.$child->name,
                    ];
                }
            }
        }
        return yii\helpers\ArrayHelper::map($arr, 'id', 'name');
    }

}
