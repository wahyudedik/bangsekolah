<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * This is the model class for table "navbar".
 *
 * @property int $id
 * @property string $name
 * @property string $url
 * @property int $level
 * @property int $position
 * @property int $parent_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 */
class Navbar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'navbar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url'], 'string'],
            [['level', 'position', 'parent_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
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
            'url' => 'Url',
            'level' => 'Level',
            'position' => 'Sort',
            'parent_id' => 'Parent Menu',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getParent()
    {
        return $this->hasOne(Navbar::className(), ['id' => 'parent_id']);
    }

    public function getChilds()
    {
        return $this->hasMany(Navbar::className(), ['parent_id' => 'id'])
            ->where(['status' => 1])
            ->orderBy(['position' => SORT_ASC]);
    }

    public static function getParentList()
    {
        $arr = Navbar::find()->where(['level' => 1])->asArray()->all();
        return yii\helpers\ArrayHelper::map($arr, 'id', 'name');
    }

    public static function getList()
    {
        $arr = Navbar::find()->asArray()->all();
        return yii\helpers\ArrayHelper::map($arr, 'id', 'name');
    }

    public static function getListTree()
    {
        $arr = [];
        $parents = Navbar::find()->where(['level' => 1])->orderBy(['position' => SORT_ASC])->all();
        foreach ($parents as $parent) {
            $arr[] = [
                'id' => $parent->id,
                'name' => $parent->name,
            ];
            $subparents = Navbar::find()->where(['parent_id' => $parent->id])->orderBy(['position' => SORT_ASC])->all();
            foreach ($subparents as $sub) {
                $arr[] = [
                    'id' => $sub->id,
                    'name' => '---- '.$sub->name,
                ];
            }
        }
        return yii\helpers\ArrayHelper::map($arr, 'id', 'name');
    }

    public function getLinkPosition()
    {
        $lastPosition = Navbar::find()->where(['level' => 1])->orderBy(['position' => SORT_DESC])->limit(1)->one()->position;
        $link = '';
        if (User::can('navbar', 'up')) {
            if ($this->position > 1) {
                $link .= Html::a('<i class="fas fa-sort-up"></i>', ['navbar/up', 'id' => $this->id], ['class' => 'btn btn-default']).' ';
            }
        }
        if (User::can('navbar', 'down')) {
            if ($this->position < $lastPosition) {
                $link .= Html::a('<i class="fas fa-sort-down"></i>', ['navbar/down', 'id' => $this->id], ['class' => 'btn btn-default']).' ';
            }
        }
        if ($this->level == 1) {
            return $link;
        }
    }
}
