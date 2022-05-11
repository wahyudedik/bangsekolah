<?php

namespace common\models;

use Yii;
use yii\helpers\Html;
use common\models\Setting;
use common\helpers\Strings;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "scholarship".
 *
 * @property integer $id
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $image
 * @property string tag
 * @property integer $read
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ScholarshipTag[] $scholarshipTags
 */
class Scholarship extends \yii\db\ActiveRecord
{
    public $tags;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'scholarship';
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
            [
                'class' => 'yii\behaviors\BlameableBehavior',
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description', 'content'], 'required'],
            [['slug', 'description', 'content', 'tag'], 'string'],
            [['read', 'created_by'], 'integer'],
            [['created_at', 'updated_at', 'tags'], 'safe'],
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
            'description' => 'Description',
            'content' => 'Content',
            'image' => 'Image',
            'read' => 'Read',
            'created_by' => 'Author',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',

            'imageSmall' => 'Image',
            'titleSort' => 'Title',
        ];
    }
    
    public function getUser()
    {
        return $this->hasOne(User::classname(), ['id' => 'created_by']);
    }

    public function getImageSmall()
    {
        if ($this->image) {
            $path = Yii::getAlias('@fronturl/uploads/scholarship/small-'.$this->image);
            return Html::img($path, ['class' => 'img img-responsive', 'style' => 'width: 40px;']);
        } else {
            $path = Yii::getAlias('@fronturl/images/default-scholarship.jpg');
            return Html::img($path, ['class' => 'img img-responsive', 'style' => 'width: 40px;']);
        }
    }

    public function getImageLarge()
    {
        if ($this->image) {
            $path = Yii::getAlias('@fronturl/uploads/scholarship/large-'.$this->image);
            return Html::img($path, ['class' => 'img img-responsive']);
        } else {
            $path = Yii::getAlias('@fronturl/images/default-scholarship.jpg');
            return Html::img($path, ['class' => 'img img-responsive']);
        }
    }

    public function getTagLabel()
    {
        $string = '';
        $tags = json_decode($this->tag);
        if ($tags) {
            foreach ($tags as $tag) {
                $string .= '<label class="label label-primary">'.$tag.'</label> ';
            }
        }
        return $string;
    }

    /*
    public function getAllScholarshipTags()
    {
        $arr = [];
        $scholarships = Scholarship::find()->all();
        foreach ($scholarships as $scholarship) {
            $tags = json_decode($scholarship->tag);
            foreach ($tags as $tag) {
                if (!in_array($tag, $arr)) {
                    $arr[] = $tag;
                }
            }
        }
        return $arr;
    }
    */

    public function getTitleSort()
    {
        return Strings::sortText($this->title, 50);
    }

    public function getScholarshipUrl()
    {
        return Yii::getAlias('@fronturl/scholarship/'.$this->slug);
    }

}
