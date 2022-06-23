<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Post;
use common\models\PostCategory;

class SiteController extends Controller
{
    public function actionIndex()
    {
        // return $this->redirect(Yii::getAlias('@backurl'));
        // return 'WELCOME TO BANKSEKOLAH';
        return $this->render('index');
        
    }

    public function actionError()
    {
        /**
         * Check if url contains news string
         */
        if (strpos(Yii::$app->request->url, 'news') !== false) {
            $lastSlug = explode('/', Yii::$app->request->url);
            $articleSlug = end($lastSlug);

            $article = Post::find()->where(['slug' => $articleSlug])->one();
            if ($article) {
                return $this->redirect(['article/detail', 'slug' => $article->slug]);
            }
    
            return $this->redirect(['article/alternative', 'slug' => $articleSlug]);
        }

        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            // $this->layout = 'yourNewLayout';
            return $this->render('error', [
            	'exception' => $exception
            ]);
        }
    }

    public function actionPrivacy()
    {
        return $this->render('privacy');
    }
}
