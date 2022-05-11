<?php

namespace common\models;

use Yii;
use yii\helpers\Html;
use common\models\Setting;
use common\helpers\Strings;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "page".
 *
 * @property integer $id
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $image
 * @property string $video
 * @property string tag
 * @property integer $read
 * @property integer $status
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $published_at
 *
 * @property PageTag[] $pageTags
 */
class Page extends \yii\db\ActiveRecord
{
    public $tags;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'page';
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
            [['title', 'description', 'content', 'image'], 'required'],
            [['slug', 'description', 'content', 'tag'], 'string'],
            [['read', 'status', 'created_by'], 'integer'],
            [['created_at', 'updated_at', 'published_at', 'tags'], 'safe'],
            [['title', 'image', 'video'], 'string', 'max' => 255],
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
            'video' => 'Video',
            'read' => 'Read',
            'created_by' => 'Author',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'published_at' => 'Published At',

            'imageSmall' => 'Image',
            'titleSort' => 'Title',
            'statusLabel' => 'Status',
            'publishedTimeAgo' => 'Published At',
        ];
    }
    
    public function getUser()
    {
        return $this->hasOne(User::classname(), ['id' => 'created_by']);
    }

    /*
    public function getPageTags()
    {
        return $this->hasMany(PageTag::className(), ['page_id' => 'id']);
    }
    */

    public function getImageSmall()
    {
        if ($this->image) {
            $explodedName = explode('.', $this->image);
            $extension = end($explodedName);
            if ($extension == 'jpg' || $extension == 'png' || $extension == 'webp') {
                $path = Yii::getAlias('@fronturl/uploads/pages/small-'.$this->image);
                return Html::img($path, ['style' => 'width: 40px;']);
            } if ($extension == 'mp4' || $extension == 'gif') {
                $path = Yii::getAlias('@fronturl/images/default-video.webp');
                return Html::img($path, ['style' => 'width: 40px;']);
            } else {
                $path = Yii::getAlias('@fronturl/images/default-page.webp');
                return Html::img($path, ['style' => 'width: 40px;']);
            }
        } else {
            $path = Yii::getAlias('@fronturl/images/default-page.webp');
            return Html::img($path, ['class' => 'img img-responsive', 'style' => 'width: 40px;']);
        }
    }

    public function getImageLarge()
    {
        if ($this->image) {
            $explodedName = explode('.', $this->image);
            $extension = end($explodedName);
            if ($extension == 'jpg' || $extension == 'png' || $extension == 'webp') {
                $path = Yii::getAlias('@fronturl/uploads/pages/large-'.$this->image);
                return Html::img($path, ['class' => 'img img-responsive']);
            } else {
                $path = Yii::getAlias('@fronturl/images/default-page.webp');
                return Html::img($path, ['class' => 'img img-responsive']);
            }
        } else {
            $path = Yii::getAlias('@fronturl/images/default-page.webp');
            return Html::img($path, ['class' => 'img img-responsive']);
        }
    }

    public function getVideoEmbed()
    {
        if ($this->video) {
            $path = Yii::getAlias('@fronturl/uploads/pages/'.$this->video);
            return '
            <video autoplay="autoplay" loop="loop" muted="muted" width="100%">
                <source src="'.$path.'" type="video/mp4" />
                <img src="'.Yii::getAlias('@fronturl/images/default-video.webp').'" width="100%" />
            </video>
            ';
        } else {
            $path = Yii::getAlias('@fronturl/images/default-video.webp');
            return Html::img($path, ['class' => 'img img-responsive']);
        }
    }

    public function getTagLabel()
    {
        $string = '';
        $tags = json_decode($this->tag);
        if ($tags) {
            foreach ($tags as $tag) {
                $string .= '<span class="badge badge-primary">'.$tag.'</span> ';
            }
        }
        return $string;
    }

    public function getTagKeywords()
    {
        $string = '';
        $tags = json_decode($this->tag);
        if ($tags) {
            foreach ($tags as $tag) {
                $string .= $tag . ',';
            }
        }
        return $string;
    }

    /*
    public function getAllPageTags()
    {
        $arr = [];
        $pages = Page::find()->all();
        foreach ($pages as $page) {
            $tags = json_decode($page->tag);
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

    public function getPageUrl()
    {
        return Yii::getAlias('@fronturl/pages/'.$this->slug);
    }

    public function getStatusLabel()
    {
        return $this->status == 0 ? 'DRAFT' : 'PUBLISHED';
    }

    public function getPublishedTimeAgo()
    {
        return $this->published_at ? Strings::timeAgo($this->published_at) : '';
    }

}
