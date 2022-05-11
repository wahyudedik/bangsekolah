<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Post;
use common\models\PostCategory;

class PostController extends Controller
{

    public function actionIndex($category = null, $page = null)
    {
        $category = PostCategory::find()->where(['slug' => $category])->one();
        if (!$category) {
            throw new \yii\web\NotFoundHttpException();
        }
        $arrCategoryId = [$category->id];
        if ($category->parent_id == 0) {
            foreach ($category->childs as $child) {
                $arrCategoryId[] = $child->id;
            }
        }
        $posts = Post::find()
            ->where(['in', 'post_category_id', $arrCategoryId])
            ->andWhere(['status' => 1])
            ->orderBy(['published_at' => SORT_DESC])
            ->limit(30)->all();
        return $this->render('index', [
            'category' => $category,
            'posts' => $posts,
        ]);
    }

    public function actionSearch($q)
    {
        $posts = Post::find()
            ->where(['like', 'title', $q])
            ->andWhere(['status' => 1])
            ->orderBy(['published_at' => SORT_DESC])
            ->limit(30)->all();
        return $this->render('search', [
            'posts' => $posts,
            'q' => $q,
        ]);
    }

    public function actionView($slug)
    {
        $post = Post::find()
        ->where(['slug' => $slug])
        ->andWhere(['status' => 1])
        ->one();
        
        if (!$post) {
            throw new \yii\web\NotFoundHttpException();
        }
        $post->read = $post->read + 1;
        $post->save();

        // get parent category and it's childs
        $parentCategoryId = $post->post_category_id;
        if ($post->category->parent_id !== 0) {
            $parentCategoryId = $post->category->parent->id;
        }
        $arrCategoryId = [$parentCategoryId];
        $category = PostCategory::findOne($parentCategoryId);
        foreach ($category->childs as $child) {
            $arrCategoryId[] = $child->id;
        }

        $relateds = Post::find()->where(['not in', 'id', $post->id])->andWhere(['in', 'post_category_id', $arrCategoryId])->andWhere(['status' => 1])->orderBy(['id' => SORT_DESC])->offset(1)->limit(4)->all();
        $recommended = Post::find()->where(['not in', 'id', $post->id])->andWhere(['in', 'post_category_id', $arrCategoryId])->andWhere(['status' => 1])->orderBy(['id' => SORT_DESC])->limit(1)->one();

        return $this->render('view', [
            'post' => $post,
            'relateds' => $relateds,
            'recommended' => $recommended,
        ]);
    }

    public function actionPublishScheduledPosts()
    {
        $scheduledPosts = Post::find()
            ->where(['status' => 0])
            ->andWhere(['<=', 'scheduled_at', new \yii\db\Expression('NOW()')])
            ->all();

        $response = '<ul>';
        foreach ($scheduledPosts as $post) {
            $post->status = 1;
            $post->published_at = $post->scheduled_at;
            if ($post->save()) {
                $response .= '<li>'. $post->title.' - '.$post->scheduled_at.'</li>';
            }
        }
        $response .= '</ul>';
        return $response;
    }
}