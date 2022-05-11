<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * This is the model class for table "role_menu".
 *
 * @property string $id
 * @property string $role_id
 * @property string $menu_id
 * @property string $parameter
 * @property string $label
 * @property integer $position
 * @property string $privileges misal: crud
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Role $role
 * @property Menu $menu
 */
class RoleMenu extends \yii\db\ActiveRecord
{
    public $json_privileges;
    public $json_parameter;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'role_menu';
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
            [['role_id', 'menu_id', 'position'], 'integer'],
            [['role_id', 'menu_id', 'privileges'], 'required'],
            [['created_at', 'updated_at', 'json_privileges', 'json_parameter'], 'safe'],
            [['privileges', 'parameter', 'label'], 'string', 'max' => 255],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['role_id' => 'id']],
            [['menu_id'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::className(), 'targetAttribute' => ['menu_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_id' => 'Role ID',
            'menu_id' => 'Menu ID',
            'parameter' => 'Parameter',
            'privileges' => 'Privileges action menu',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'menu_id']);
    }

    public function getLinkPosition()
    {
        $lastPosition = RoleMenu::find()->where(['role_id' => $this->role_id])->orderBy(['position' => SORT_DESC])->limit(1)->one()->position;
        $link = '';
        if (User::can('role-menu', 'up')) {
            if ($this->position > 1) {
                $link .= '<button id="up-'.$this->id.'" class="btn btn-primary"><i class="fas fa-arrow-up"></i></button>';
                $link .= '
                <script>
                    $("#up-'.$this->id.'").click(function(){
                        $.post("'.Url::to(['role-menu/up', 'id' => $this->id]).'", {}, function(result){
                            $("#grid-role-menu").html(result);
                        })
                    });
                </script>';
            }
        }
        if (User::can('role-menu', 'down')) {
            if ($this->position < $lastPosition) {
                $link .= '<button id="down-'.$this->id.'" class="btn btn-primary"><i class="fas fa-arrow-down"></i></button>';
                $link .= '
                <script>
                    $("#down-'.$this->id.'").click(function(){
                        $.post("'.Url::to(['role-menu/down', 'id' => $this->id]).'", {}, function(result){
                            $("#grid-role-menu").html(result);
                        })
                    });
                </script>';
            }
        }
        return $link;
    }

    public function getPrivilegeRaw()
    {
        $string = '';
        $json_privileges = json_decode($this->privileges);
        if ($json_privileges) {
            foreach ($json_privileges as $priv) {
                $string .= '<span class="badge badge-default">'.$priv.'</span>';
            }
        }
        return $string;
    }

    public function getParameterRaw()
    {
        $string = '';
        $json_parameter = json_decode($this->parameter);
        if ($json_parameter) {
            foreach ($json_parameter as $param) {
                $string .= $param.', ';
            }
        }
        return $string;
    }

    public function getParentChildName()
    {
        if ($this->menu->parent) {
            return '<span style="padding-left: 30px;">-> '.($this->menu->name).'</span>';
        } else {
            return ($this->label ? $this->label : $this->menu->name);
        }
    }

}
