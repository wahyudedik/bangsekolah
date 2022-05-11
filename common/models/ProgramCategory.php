<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Setting;
use common\helpers\Strings;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "program_category".
 *
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string $image
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Program[] $programs
 */
class ProgramCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'program_category';
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
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slug', 'description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'image'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'slug' => 'Slug',
            'name' => 'Category',
            'image' => 'Image',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrograms()
    {
        return $this->hasMany(Program::className(), ['program_category_id' => 'id']);
    }

    public function getImageSmall()
    {
        if ($this->image) {
            $path = Yii::getAlias('@fronturl/uploads/program-category/small-'.$this->image);
            return Html::img($path, ['class' => 'img img-responsive', 'style' => 'width: 40px;']);
        } else {
            $path = Yii::getAlias('@fronturl/images/default-banner.jpg');
            return Html::img($path, ['class' => 'img img-responsive', 'style' => 'width: 40px;']);
        }
    }

    public function getImageLarge()
    {
        if ($this->image) {
            $path = Yii::getAlias('@fronturl/uploads/program-category/large-'.$this->image);
            return Html::img($path, ['class' => 'img img-responsive']);
        } else {
            $path = Yii::getAlias('@fronturl/images/default-banner.jpg');
            return Html::img($path, ['class' => 'img img-responsive']);
        }
    }

    public static function getList()
    {
        return \yii\helpers\ArrayHelper::map(
            PostCategory::find()->asArray()->all(),
            'id', 'name'
        );
    }
}
