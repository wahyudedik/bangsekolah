<?php

namespace common\models;

use Yii;
use yii\helpers\Html;
use common\models\Setting;
use common\helpers\Strings;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "center".
 *
 * @property integer $id
 * @property string $slug
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string $latitude
 * @property string $longitude
 * @property string $content
 * @property string $image
 * @property string $created_at
 * @property string $updated_at
 *
 */
class Center extends \yii\db\ActiveRecord
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'center';
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
            [['name', 'address', 'phone', 'content'], 'required'],
            [['slug', 'address', 'content'], 'string'],
            [['created_at', 'updated_at', 'tags'], 'safe'],
            [['name', 'image', 'latitude', 'longitude', 'image'], 'string', 'max' => 255],
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
            'name' => 'Name',
            'address' => 'Address',
            'phone' => 'Phone',
            'content' => 'Content',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'image' => 'Image',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'imageSmall' => 'Image',
        ];
    }

    public function getImageSmall()
    {
        if ($this->image) {
            $path = Yii::getAlias('@fronturl/uploads/center/small-'.$this->image);
            return Html::img($path, ['class' => 'img img-responsive', 'style' => 'width: 40px;']);
        } else {
            $path = Yii::getAlias('@fronturl/images/default-center.jpg');
            return Html::img($path, ['class' => 'img img-responsive', 'style' => 'width: 40px;']);
        }
    }

    public function getImageLarge()
    {
        if ($this->image) {
            $path = Yii::getAlias('@fronturl/uploads/center/large-'.$this->image);
            return Html::img($path, ['class' => 'img img-responsive']);
        } else {
            $path = Yii::getAlias('@fronturl/images/default-center.jpg');
            return Html::img($path, ['class' => 'img img-responsive']);
        }
    }

    public function getAddressSort()
    {
        return Strings::sortText($this->address, 50);
    }

    public static function getList()
    {
        return \yii\helpers\ArrayHelper::map(
            Center::find()->asArray()->all(),
            'id', 'name'
        );
    }

}
