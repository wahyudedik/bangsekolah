<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "posts".
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int $author
 * @property string $short_description
 * @property string $content
 * @property int $views
 * @property int $on_landing
 * @property string $hastag
 * @property string $published_at
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class StrapiPosts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'posts';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('strapi');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'author', 'views', 'on_landing', 'created_by', 'updated_by'], 'integer'],
            [['content', 'hastag'], 'string'],
            [['title'], 'string', 'max' => 83],
            [['slug'], 'string', 'max' => 82],
            [['short_description'], 'string', 'max' => 179],
            [['published_at', 'created_at', 'updated_at'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'slug' => 'Slug',
            'author' => 'Author',
            'short_description' => 'Short Description',
            'content' => 'Content',
            'views' => 'Views',
            'on_landing' => 'On Landing',
            'hastag' => 'Hastag',
            'published_at' => 'Published At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
