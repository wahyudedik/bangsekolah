<?php

namespace common\models;

use Yii;
use yii\helpers\Html;
use common\models\Setting;
use common\helpers\Strings;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "post".
 *
 * @property integer $id
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $image
 * @property string $video
 * @property integer $post_category_id
 * @property string tag
 * @property integer $read
 * @property integer $status
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $scheduled_at
 * @property string $published_at
 *
 * @property PostTag[] $postTags
 */
class Post extends \yii\db\ActiveRecord
{
    public $tags;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
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
            // [
            //     'class' => SluggableBehavior::className(),
            //     'attribute' => 'title',
            //     'immutable' => false,
            //     'ensureUnique' => true,
            //     'slugAttribute' => 'slug',
            // ], 
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
            [['title', 'description', 'content', 'post_category_id', 'image'], 'required'],
            [['slug', 'description', 'content', 'tag'], 'string'],
            [['read', 'status', 'post_category_id', 'created_by'], 'integer'],
            [['created_at', 'updated_at', 'scheduled_at', 'published_at', 'tags'], 'safe'],
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
            'post_category_id' => 'Category',
            'read' => 'Read',
            'created_by' => 'Author',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'scheduled_at' => 'Scheduled At',
            'published_at' => 'Published At',

            'imageSmall' => 'Image',
            'titleSort' => 'Title',
            'statusLabel' => 'Status',
            'publishedTimeAgo' => 'Published At',
            'publishedDate' => 'Published',
        ];
    }
    
    public function getCategory()
    {
        return $this->hasOne(PostCategory::classname(), ['id' => 'post_category_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::classname(), ['id' => 'created_by']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::classname(), ['id' => 'created_by']);
    }

    public function getUpdatedBy()
    {
        if ($this->updated_by) {
            return $this->hasOne(User::classname(), ['id' => 'updated_by']);
        } else {
            return $this->hasOne(User::classname(), ['id' => 'created_by']);
        }
    }

    /*
    public function getPostTags()
    {
        return $this->hasMany(PostTag::className(), ['post_id' => 'id']);
    }
    */

    public function getImageSmall()
    {
        if ($this->image) {
            $explodedName = explode('.', $this->image);
            $extension = end($explodedName);
            if ($extension == 'jpg' || $extension == 'png' || $extension == 'webp' || $extension == 'jpeg') {
                $path = Yii::getAlias('@fronturl/uploads/posts/small-'.$this->image);
                return Html::img($path, ['style' => 'width: 40px; border-radius: 5px;']);
            } if ($extension == 'mp4' || $extension == 'gif') {
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
                $path = Yii::getAlias('@fronturl/uploads/posts/large-'.$this->image);
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
            $path = Yii::getAlias('@fronturl/uploads/posts/'.$this->video);
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

    public function getHashtagLabel()
    {
        $string = '';
        $tags = json_decode($this->tag);
        if ($tags) {
            foreach ($tags as $tag) {
                $string .= '<span class="badge badge-primary">#'.$tag.'</span> ';
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
    public function getAllPostTags()
    {
        $arr = [];
        $posts = Post::find()->all();
        foreach ($posts as $post) {
            $tags = json_decode($post->tag);
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

    public function getPostUrl()
    {
        return Yii::getAlias('@fronturl/posts/'.$this->slug);
    }

    public function getStatusLabel()
    {
        if ($this->status == 1) {
            return 'PUBLISHED';
        } else {
            if ($this->scheduled_at !== null) {
                return 'SCHEDULED';
            } else {
                return 'DRAFT';
            }
        }
    }

    public function getPublishedTimeAgo()
    {
        return $this->published_at ? Strings::timeAgo($this->published_at) : '';
    }

    public function getCreatedDate()
    {
        return $this->created_at ? date('d M Y', strtotime($this->created_at)) : '';
    }

    public function getUpdatedDate()
    {
        return $this->updated_at ? date('d M Y', strtotime($this->updated_at)) : '';
    }

    public function getPublishedDate()
    {
        return $this->published_at ? date('d M Y', strtotime($this->published_at)) : 'Not yet';
    }

}
