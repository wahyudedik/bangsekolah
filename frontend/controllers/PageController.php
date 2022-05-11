<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\base\ViewNotFoundException;
use common\models\Page;

class PageController extends Controller
{
    public function actionView($slug)
    {
        $page = Page::find()->where(['slug' => $slug])->one();
        if ($page) {
            $page->read = $page->read + 1;
            $page->save();
            return $this->render('view', [
                'page' => $page
            ]);
        } else {
        	try {
			    return $this->render($slug);
			} catch (ViewNotFoundException $e) {
			    throw new \yii\web\NotFoundHttpException();
			}
        }
    }
}