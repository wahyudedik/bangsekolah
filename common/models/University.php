<?php

namespace common\models;

use Yii;
use yii\helpers\Html;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "university".
 *
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string $logo
 * @property string $image
 * @property string $logo_country
 * @property string $address
 * @property string $id_content
 * @property string $en_content
 * @property string $latitude
 * @property string $longitude
 * @property string $country
 * @property string $created_at
 * @property string $updated_at
 *
 */
class University extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'university';
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
            [['name', 'country', 'id_content', 'en_content'], 'required'],
            [['slug', 'address', 'id_content', 'en_content'], 'string'],
            [['created_at', 'updated_at', 'json_majors', 'json_programs'], 'safe'],
            [['name', 'logo', 'image', 'logo_country', 'latitude', 'longitude', 'country'], 'string', 'max' => 255],
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
            'name' => 'Name',
            'logo' => 'Logo',
            'image' => 'Image',
            'logo_country' => 'Image Country',
            'address' => 'Address',
            'id_content' => 'Id Content',
            'en_content' => 'En Content',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'country' => 'Country',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getImageSmall()
    {
        if ($this->image) {
            $path = Yii::getAlias('@fronturl/uploads/university/small-'.$this->image);
            return Html::img($path, ['class' => 'img img-responsive', 'style' => 'width: 40px;']);
        } else {
            $path = Yii::getAlias('@fronturl/images/default-university.jpg');
            return Html::img($path, ['class' => 'img img-responsive', 'style' => 'width: 40px;']);
        }
    }

    public function getImageLarge()
    {
        if ($this->image) {
            $path = Yii::getAlias('@fronturl/uploads/university/large-'.$this->image);
            return Html::img($path, ['class' => 'img img-responsive']);
        } else {
            $path = Yii::getAlias('@fronturl/images/default-university.jpg');
            return Html::img($path, ['class' => 'img img-responsive']);
        }
    }

    public function getLogoSmall()
    {
        if ($this->logo) {
            $path = Yii::getAlias('@fronturl/uploads/university/logo-'.$this->logo);
            return Html::img($path, ['class' => 'img img-responsive', 'style' => 'width:50px;']);
        } else {
            $path = Yii::getAlias('@fronturl/images/default-university.jpg');
            return Html::img($path, ['class' => 'img img-responsive']);
        }
    }

    public function getLogoCountrySmall()
    {
        if ($this->logo_country) {
            $path = Yii::getAlias('@fronturl/uploads/university/country-'.$this->logo_country);
            return Html::img($path, ['class' => 'img img-responsive', 'style' => 'width:50px;']);
        } else {
            $path = Yii::getAlias('@fronturl/images/default-university.jpg');
            return Html::img($path, ['class' => 'img img-responsive']);
        }
    }

    public static function getList()
    {
        return \yii\helpers\ArrayHelper::map(
            University::find()->asArray()->all(),
            'id', 'name'
        );
    }
}
