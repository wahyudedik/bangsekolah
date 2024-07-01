<?php

namespace common\models;

use common\helpers\Strings;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $image
 * @property string $video
 * @property int $post_category_id
 * @property string $tag
 * @property int $read
 * @property string $status ACTIVE, NONACTIVE
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $scheduled_at
 * @property string $published_at
 */
class Post extends \yii\db\ActiveRecord
{
    public $tags;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
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
                'class' => 'yii\behaviors\BlameableBehavior',
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slug', 'description', 'content', 'tag', 'status'], 'string'],
            [['post_category_id', 'read', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at', 'scheduled_at', 'published_at', 'tags'], 'safe'],
            [['title', 'image', 'video'], 'string', 'max' => 255],
            [['post_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => PostCategory::className(), 'targetAttribute' => ['post_category_id' => 'id']],
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
            'title' => 'Title',
            'description' => 'Description',
            'content' => 'Content',
            'image' => 'Image',
            'video' => 'Video',
            'post_category_id' => 'Post Category ID',
            'tag' => 'Tag',
            'read' => 'Read',
            'status' => 'Status',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'scheduled_at' => 'Scheduled At',
            'published_at' => 'Published At',

            'imageSmall' => 'Image',
            'titleSort' => 'Title',
            'publishedTimeAgo' => 'Published At',
            'publishedDate' => 'Published',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostCategory()
    {
        return $this->hasOne(PostCategory::className(), ['id' => 'post_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostImages()
    {
        return $this->hasMany(PostImage::className(), ['post_id' => 'id']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::classname(), ['id' => 'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::classname(), ['id' => 'created_by']);
    }

    public function getImageSmall()
    {
        if ($this->image) {
            $explodedName = explode('.', $this->image);
            $extension = end($explodedName);
            if ($extension == 'jpg' || $extension == 'png' || $extension == 'webp' || $extension == 'jpeg' || $extension == 'JPG' || $extension == 'PNG' || $extension == 'WEBP' || $extension == 'JPEG') {
                $path = Yii::getAlias('@fronturl/uploads/posts/small-' . $this->image);
                return Html::img($path, ['style' => 'width: 40px; border-radius: 5px;']);
            }
            if ($extension == 'mp4' || $extension == 'gif') {
                $path = Yii::getAlias('@fronturl/images/default-video.webp');
                return Html::img($path, ['style' => 'width: 40px; border-radius: 5px;']);
            } else {
                $path = Yii::getAlias('@fronturl/images/default-post.webp');
                return Html::img($path, ['style' => 'width: 40px; border-radius: 5px;']);
            }
        } else {
            $path = Yii::getAlias('@fronturl/images/default-post.webp');
            return Html::img($path, ['class' => 'img img-responsive', 'style' => 'width: 40px; border-radius: 5px;']);
        }
    }

    public function getImageLarge()
    {
        if ($this->image) {
            $explodedName = explode('.', $this->image);
            $extension = end($explodedName);
            if ($extension == 'jpg' || $extension == 'png' || $extension == 'webp' || $extension == 'jpeg') {
                $path = Yii::getAlias('@fronturl/uploads/posts/large-' . $this->image);
                return Html::img($path, ['class' => 'img img-responsive']);
            } else {
                $path = Yii::getAlias('@fronturl/images/default-post.webp');
                return Html::img($path, ['class' => 'img img-responsive']);
            }
        } else {
            $path = Yii::getAlias('@fronturl/images/default-post.webp');
            return Html::img($path, ['class' => 'img img-responsive']);
        }
    }

    public function getVideoEmbed()
    {
        if ($this->video) {
            $path = Yii::getAlias('@fronturl/uploads/posts/' . $this->video);
            return '
            <video autoplay="autoplay" loop="loop" muted="muted" width="100%">
                <source src="' . $path . '" type="video/mp4" />
                <img src="' . Yii::getAlias('@fronturl/images/default-video.webp') . '" width="100%" />
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
                $string .= '<span class="badge badge-primary">' . $tag . '</span> ';
            }
        }
        return $string;
    }

    public function getHashtagLabel()
    {
        $string = '<a>JAGOAN DIGITAL</a>';
        $tags = json_decode($this->tag);
        if ($tags) {
            foreach ($tags as $tag) {
                $string .= '<a>' . $tag . '</a>';
                //$string .= '<span class="badge badge-primary">#'.$tag.'</span> ';
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

    public function getTitleSort()
    {
        return Strings::sortText($this->title, 5);
    }

    public function getDescSort()
    {
        return Strings::sortText($this->description, 30);
    }

    public function getPostUrl()
    {
        return Yii::getAlias('@fronturl/post/' . $this->slug);
    }

    public function getPublishedDate()
    {
        return $this->published_at ? date('d M Y', strtotime($this->published_at)) : 'Not yet';
    }
}
