<?php

namespace common\models;

use Yii;
use yii\helpers\Html;
use common\models\Setting;
use common\helpers\Strings;

/**
 * This is the model class for table "testimony".
 *
 * @property integer $id
 * @property string $category
 * @property string $name
 * @property string $occupation
 * @property string $content
 * @property string $image
 * @property string $created_at
 * @property string $updated_at
 *
 */
class Testimony extends \yii\db\ActiveRecord
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'testimony';
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
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'occupation', 'content'], 'required'],
            [['occupation', 'content'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'category', 'image'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'occupation' => 'Occupation/School',
            'category' => 'Category',
            'content' => 'Content',
            'image' => 'Image',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'imageSmall' => 'Image',
        ];
    }

    public function getImageSmall()
    {
        if ($this->image) {
            $path = Yii::getAlias('@fronturl/uploads/testimony/small-'.$this->image);
            return Html::img($path, ['class' => 'img img-responsive', 'style' => 'width: 40px;']);
        } else {
            $path = Yii::getAlias('@fronturl/images/default-testimony.jpg');
            return Html::img($path, ['class' => 'img img-responsive', 'style' => 'width: 40px;']);
        }
    }

    public function getImageLarge()
    {
        if ($this->image) {
            $path = Yii::getAlias('@fronturl/uploads/testimony/large-'.$this->image);
            return Html::img($path, ['class' => 'img img-responsive']);
        } else {
            $path = Yii::getAlias('@fronturl/images/default-testimony.jpg');
            return Html::img($path, ['class' => 'img img-responsive']);
        }
    }

    public function getContentSort()
    {
        return Strings::sortText($this->content, 50);
    }

    public function getCategoryName()
    {
        switch ($this->category) {
            case 'study-overseas':
                return 'Study Overseas';
                break;
            case 'vista-english':
                return 'Vista English';
                break;
            case 'vista-corporate':
                return 'English for Corporate';
                break;
            default:
                return 'Overseas Study';
                break;
        }
    }
}
