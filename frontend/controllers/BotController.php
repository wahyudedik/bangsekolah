<?php
namespace frontend\controllers;

use DOMDocument;
use Yii;
use yii\web\Controller;
use common\models\Post;
use common\models\PostCategory;
use yii\imagine\Image;

class BotController extends Controller
{

    public function actionGetContentImages($id = null)
    {
        if ($id) {
            $posts = Post::find()
            ->where(['in', 'id', $id])
            ->andWhere(['like', 'content', 'src="/uploads'])
            ->andWhere(['is not', 'image', null])
            ->limit(6)
            ->all();
        } else {
            $posts = Post::find()
            ->where(['like', 'content', 'src="/uploads'])
            ->andWhere(['is not', 'image', null])
            ->limit(6)
            ->all();            
        }
        $response = '<meta http-equiv="refresh" content="30">';
        $response .= '<ul>';
        foreach ($posts as $post) {

            $response .= '<li>';            
            $response .= $post->title;
            $response .= '<ul>';
            $doc = new DOMDocument();
            @$doc->loadHTML($post->content);
            $tags = $doc->getElementsByTagName('img');

            $content = $post->content;
            foreach ($tags as $tag) {
                $src = $tag->getAttribute('src');
                if (substr($src, 0, 9) === '/uploads/') {
                    $filename = str_replace('/uploads/', '', $src);
                    // get extension
                    $extension = pathinfo($filename)['extension'];
                    if (in_array($extension, ['jpg', 'jpeg', 'png', 'JPG'])) {

                        // copy image and resize with maintain aspect ratio
                        $strapiImagePath = Yii::getAlias('@uploads/strapi-uploads/'.$filename);
                        $newFilename = Yii::$app->security->generateRandomString().'.'.$extension;
                        $targetImagePath = Yii::getAlias('@uploads/posts/'.$newFilename);                        
                        $imageSize = getimagesize($strapiImagePath);
                        if ($imageSize[0] >= 900 && $imageSize[1] >= 900) {
                            Image::thumbnail($strapiImagePath, 900, ($imageSize[1]/($imageSize[0]/900)))->save($targetImagePath, ['quality' => 100]);
                        } else {
                            Image::thumbnail($strapiImagePath, $imageSize[0], $imageSize[1])->save($targetImagePath, ['quality' => 100]);
                        }

                        // replace content
                        $content = str_replace('/uploads/'.$filename, 'https://thesmedia.id/uploads/posts/'.$newFilename, $content);

                        $response .= '<li>'.$filename.' -> '.$newFilename.'</li>';
                    }
                }
            }
            $post->content = $content;
            if (!$post->save()) {
                foreach($post->errors as $error) {
                    return $error[0];
                }
            }
            $response .= '</ul>';
            $response .= '</li>';            
        } 
        $response .= '</ul>';
        return $response;
    }

    public function actionGenerateUpdateContent()
    {
        $arrId = [16,21,22,39,41,42,43,48,50,57,58,59,63,69,75,78,82,89,90,91,92,95,96,99,100,103,105,107,116,117,118,119,121,127,129,131,132,133,135,137,138,140,142,143,145,146,147,151,153,155,156,157,158,160,161,168,173,184,185,186,187,188,191,192,193,197,200,203,204,206,208,212,213,214,217,218,221,226,228,231,235,236,238,239,240,249,250,251,252,256];
        $posts = Post::find()->where(['in', 'id', $arrId])->all();
        foreach ($posts as $post) {
            echo "update post set content='".htmlspecialchars($post->content)."' where id='".$post->id."';";
        }
    }

    public function actionSetImageResponsive()
    {
        $arrId = [16,21,22,39,41,42,43,48,50,57,58,59,63,69,75,78,82,89,90,91,92,95,96,99,100,103,105,107,116,117,118,119,121,127,129,131,132,133,135,137,138,140,142,143,145,146,147,151,153,155,156,157,158,160,161,168,173,184,185,186,187,188,191,192,193,197,200,203,204,206,208,212,213,214,217,218,221,226,228,231,235,236,238,239,240,249,250,251,252,256];
        $posts = Post::find()->where(['in', 'id', $arrId])->all();
        $count = 0;
        foreach ($posts as $post) {
            $newContent = str_replace('<img src="', '<img class="img-responsive" src="', $post->content);
            $post->content = $newContent;
            if ($post->save()) {
                $count++;
            } else {
                $post->image = 'dummy';
                if ($post->save()) {
                    $count++;
                } else {
                    foreach ($post->errors as $error) {
                        return $error[0];
                    }
                }
            }
        }
        return $count.' UPDATED';
    }
}