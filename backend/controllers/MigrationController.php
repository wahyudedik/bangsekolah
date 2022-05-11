<?php

namespace backend\controllers;

use Yii;
use yii\imagine\Image;
use common\helpers\Strings;
use common\models\Post;
use common\models\Navbar;
use common\models\PostCategory;
use backend\models\StrapiPosts;
use backend\models\StrapiTags;
use backend\models\StrapiPostsTags;
use backend\models\StrapiUploadFile;
use backend\models\StrapiUploadFileMorph;

class MigrationController extends \yii\web\Controller
{
    public function actionMigratePost()
    {
        $saved = 0;
        $strapiPosts = StrapiPosts::find()->all();
        foreach ($strapiPosts as $strapiPost) {
            $post = new Post();
            $post->title = $strapiPost->title;
            $post->slug = $strapiPost->slug;
            $post->description = $strapiPost->short_description;
            $post->created_by = $strapiPost->created_by;
            $post->updated_by = $strapiPost->updated_by;
            $post->content = $strapiPost->content;
            $post->published_at = $strapiPost->published_at;
            $post->created_at = $strapiPost->created_at;
            $post->updated_at = $strapiPost->updated_at;
            $post->image = null;
            $post->status = 1;
            $post->post_category_id = 1;
            if ($post->save()) {
                $saved++;
            }
        }
        return $saved.' POST SAVED';
    }

    public function actionDownloadImage()
    {
        $saved = 0;
        $strapiPosts = StrapiPosts::find()->all();
        $response = '<ul>';
        foreach ($strapiPosts as $strapiPost) {
            if ($saved < 30) {
                $post = Post::find()->where(['slug' => $strapiPost->slug])->andWhere(['image' => null])->one();
                $strapiUploadFileMorph = StrapiUploadFileMorph::find()->where(['related_type' => 'posts', 'related_id' => $strapiPost->id])->one();
                if ($strapiUploadFileMorph && $post) {
                    $strapiUploadFile = StrapiUploadFile::findOne($strapiUploadFileMorph->upload_file_id);
                    $explodedName = explode('.', $strapiUploadFile->name);
                    $extension = end($explodedName);
                    $temporaryName = Yii::$app->security->generateRandomString() . ".{$extension}";
                    if ($extension == 'jpg' || $extension == 'png' || $extension == 'jpeg') {

                        // download image
                        $temporaryName = Yii::$app->security->generateRandomString() . ".{$extension}";
                        $path = Yii::getAlias('@uploads/posts/'.$temporaryName);
                        $ch = curl_init ('https://strapi.thesmedia.id'.$strapiUploadFile->url);
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
                        $raw=curl_exec($ch);
                        curl_close ($ch);
                        if(file_exists($path)){
                            unlink($path);
                        }
                        $fp = fopen($path, 'x');
                        fwrite($fp, $raw);
                        fclose($fp);

                        // create thumbnail & resize
                        Image::thumbnail($path, 1240, 892)->save(Yii::getAlias('@uploads/posts/large-'.$temporaryName), ['quality' => 100]);
                        Image::thumbnail($path, 620, 446)->save(Yii::getAlias('@uploads/posts/small-'.$temporaryName), ['quality' => 100]);
                        @unlink($path);

                        // update image to post
                        $post->image = $temporaryName;
                        $post->save();

                        $response .= '<li>'.$strapiPost->title .' => https://strapi.thesmedia.id'.$strapiUploadFile->url.'</li>';
                        $saved++;
                    }
                }
            }
        }
        $response .= '<li>'.$saved.' IMAGE DOWNLOADED</li>';
        $response .= '</ul>';
        return $response;
    }

    public function actionGetCategory()
    {
        $saved = 0;
        $strapiPosts = StrapiPosts::find()->all();
        foreach ($strapiPosts as $strapiPost) {
            $strapiPostsTags = StrapiPostsTags::find()->where(['post_id' => $strapiPost->id])->all();
            foreach ($strapiPostsTags as $strapiPostsTag) {
                $strapiTags = StrapiTags::findOne($strapiPostsTag->tag_id);
                if ($strapiTags && $strapiTags->tag !== '') {
                    $category = PostCategory::find()->where(['slug' => $strapiTags->slug])->one();
                    if ($category) {
                        $post = Post::find()->where(['slug' => $strapiPost->slug])->one();
                        if ($post) {
                            $post->post_category_id = $category->id;
                            if ($post->save()) {
                                $saved++;
                            }
                        }
                    }
                }
            }
        }
        return $saved.' POST CATEGORY SAVED';
    }

    public function actionSetNavbar()
    {
        $saved = 0;
        $navbars = Navbar::find()->all();
        foreach ($navbars as $navbar) {
            $navbar->url = Yii::getAlias('@fronturl').'/category/'.Strings::sluggify($navbar->name);
            $navbar->save();
            $saved++;
        }
        return $saved.' NAVBAR SAVED';
    }

    public function actionUpdateAuthor()
    {
        $saved = 0;
        $strapiPosts = StrapiPosts::find()->all();
        foreach ($strapiPosts as $strapiPost) {
            $post = Post::find()->where(['slug' => $strapiPost->slug])->one();
            if ($post) {
                $post->created_by = $strapiPost->created_by;
                $post->updated_by = $strapiPost->updated_by;
                $post->read = 0;
                if ($post->status == 1) {
                    if ($post->published_at == null) {
                        $post->published_at = $post->updated_at;
                    }
                }
                if ($post->save()) {
                    $saved++;
                }
            }
        }
        return $saved.' AUTHOR UPDATED';
    }

}
