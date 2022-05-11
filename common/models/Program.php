<?php

namespace common\models;

use Yii;
use yii\helpers\Html;
use common\models\Setting;
use common\helpers\Strings;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "program".
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
 * @property ProgramTag[] $programTags
 */
class Program extends \yii\db\ActiveRecord
{
    public $tags;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'program';
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
        return $this->hasOne(ProgramCategory::classname(), ['id' => 'program_category_id']);
    }

    public function getContents()
    {
        return $this->hasMany(ProgramContent::className(), ['program_id' => 'id']);
    }

    public function getFiles()
    {
        return $this->hasMany(ProgramFile::className(), ['program_id' => 'id']);
    }

    public function getImageSmall()
    {
        if ($this->image) {
            $path = Yii::getAlias('@fronturl/uploads/program/small-'.$this->image);
            return Html::img($path, ['class' => 'img img-responsive', 'style' => 'width: 40px;']);
        } else {
            $path = Yii::getAlias('@fronturl/images/default-program.jpg');
            return Html::img($path, ['class' => 'img img-responsive', 'style' => 'width: 40px;']);
        }
    }

    public function getImageLarge()
    {
        if ($this->image) {
            $path = Yii::getAlias('@fronturl/uploads/program/large-'.$this->image);
            return Html::img($path, ['class' => 'img img-responsive']);
        } else {
            $path = Yii::getAlias('@fronturl/images/default-program.jpg');
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
    public function getAllProgramTags()
    {
        $arr = [];
        $programs = Program::find()->all();
        foreach ($programs as $program) {
            $tags = json_decode($program->tag);
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

    public function getProgramUrl()
    {
        return Yii::getAlias('@fronturl/program/'.$this->slug);
    }

}
