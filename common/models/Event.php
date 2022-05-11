<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Setting;
use common\helpers\Strings;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "event".
 *
 * @property integer $id
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property string $url
 * @property string $image
 * @property integer $position
 * @property string $created_at
 * @property string $updated_at
 *
 */
class Event extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event';
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
                'attribute' => 'title',
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
            [['title', 'url'], 'required'],
            [['slug', 'url', 'description'], 'string'],
            [['position'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'image'], 'string', 'max' => 255],
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
            'title' => 'Title',
            'url' => 'URL',
            'description' => 'Description',
            'image' => 'Image',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',

            'imageSmall' => 'Image',
            'titleSort' => 'Title',
        ];
    }
    
    public function getImageSmall()
    {
        if ($this->image) {
            $path = Yii::getAlias('@fronturl/uploads/event/small-'.$this->image);
            return Html::img($path, ['class' => 'img img-responsive', 'style' => 'width: 40px;']);
        } else {
            $path = Yii::getAlias('@fronturl/images/default-event.jpg');
            return Html::img($path, ['class' => 'img img-responsive', 'style' => 'width: 40px;']);
        }
    }

    public function getImageLarge()
    {
        if ($this->image) {
            $path = Yii::getAlias('@fronturl/uploads/event/large-'.$this->image);
            return Html::img($path, ['class' => 'img img-responsive']);
        } else {
            $path = Yii::getAlias('@fronturl/images/default-event.jpg');
            return Html::img($path, ['class' => 'img img-responsive']);
        }
    }

    public function getTitleSort()
    {
        return Strings::sortText($this->title, 50);
    }

    public function getLinkPosition()
    {
        $lastPosition = Event::find()->orderBy(['position' => SORT_DESC])->limit(1)->one()->position;
        $link = '';
        if ($this->position > 1) {
            $link .= '<button id="up-'.$this->id.'" class="btn btn-primary"><i class="fa fa-level-up"></i></button>';
            $link .= '
            <script>
                $("#up-'.$this->id.'").click(function(){
                    $.post("'.Url::to(['event/up', 'id' => $this->id]).'", {}, function(result){
                        $("#grid-event").html(result);
                    })
                });
            </script>';
        }
        if ($this->position < $lastPosition) {
            $link .= '<button id="down-'.$this->id.'" class="btn btn-primary"><i class="fa fa-level-down"></i></button>';
            $link .= '
            <script>
                $("#down-'.$this->id.'").click(function(){
                    $.post("'.Url::to(['event/down', 'id' => $this->id]).'", {}, function(result){
                        $("#grid-event").html(result);
                    })
                });
            </script>';
        }
        return $link;
    }
}
