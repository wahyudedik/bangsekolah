<?php

namespace common\models;

use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "post_category".
 *
 * @property integer $id
 * @property string $slug
 * @property string $name
 * @property integer $parent_id
 * @property string $created_at
 * @property string $updated_at
 */
class PostCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post_category';
    }

    /**
     * @timestamp
     */
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
             [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'immutable' => false,
                'ensureUnique' => true,
                'slugAttribute' => 'slug',
            ], 
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['parent_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['slug', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'slug' => 'Slug',
            'name' => 'Category',
            'parent_id' => 'Parent',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getParent()
    {
        return $this->hasOne(PostCategory::className(), ['id' => 'parent_id']);
    }

    public function getChilds()
    {
        return $this->hasMany(PostCategory::className(), ['parent_id' => 'id']);
    }

    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['post_category_id' => 'id']);
    }

    public static function getList()
    {
        return \yii\helpers\ArrayHelper::map(
            PostCategory::find()->asArray()->all(),
            'id', 'name'
        );
    }

    public static function getListTree()
    {
        $arr = [];
        $parents = PostCategory::find()->where(['parent_id' => 0])->all();
        foreach ($parents as $parent) {
            $arr[] = [
                'id' => $parent->id,
                'name' => $parent->name,
            ];
            $subparents = PostCategory::find()->where(['parent_id' => $parent->id])->all();
            foreach ($subparents as $sub) {
                $arr[] = [
                    'id' => $sub->id,
                    'name' => '---- '.$sub->name,
                ];
            }
        }
        return yii\helpers\ArrayHelper::map($arr, 'id', 'name');
    }
}
