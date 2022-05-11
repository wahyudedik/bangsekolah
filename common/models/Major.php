<?php

namespace common\models;

use Yii;
use yii\helpers\Html;
use common\models\Setting;
use common\helpers\Strings;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "major".
 *
 * @property integer $id
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property string $id_content
 * @property string $en_content
 * @property string $image
 * @property string tag
 * @property integer $read
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property MajorTag[] $majorTags
 */
class Major extends \yii\db\ActiveRecord
{
    public $tags;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'major';
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
            [['title', 'description', 'id_content', 'en_content'], 'required'],
            [['slug', 'description', 'id_content', 'en_content', 'tag'], 'string'],
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
            'id_content' => 'Id Content',
            'en_content' => 'En Content',
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

    public function getCategory()
    {
        return $this->hasOne(MajorCategory::classname(), ['id' => 'major_category_id']);
    }

    public function getImageSmall()
    {
        if ($this->image) {
            $path = Yii::getAlias('@fronturl/uploads/major/small-'.$this->image);
            return Html::img($path, ['class' => 'img img-responsive', 'style' => 'width: 40px;']);
        } else {
            $path = Yii::getAlias('@fronturl/images/default-major.jpg');
            return Html::img($path, ['class' => 'img img-responsive', 'style' => 'width: 40px;']);
        }
    }

    public function getImageLarge()
    {
        if ($this->image) {
            $path = Yii::getAlias('@fronturl/uploads/major/large-'.$this->image);
            return Html::img($path, ['class' => 'img img-responsive']);
        } else {
            $path = Yii::getAlias('@fronturl/images/default-major.jpg');
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
    public function getAllMajorTags()
    {
        $arr = [];
        $majors = Major::find()->all();
        foreach ($majors as $major) {
            $tags = json_decode($major->tag);
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

    public function getMajorUrl()
    {
        return Yii::getAlias('@fronturl/major/'.$this->category->slug.'/'.$this->slug);
    }

    public static function getList()
    {
        return \yii\helpers\ArrayHelper::map(
            Major::find()->asArray()->all(),
            'title', 'title'
        );
    }
}
