<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Visitor;
use common\models\Center;
use common\models\Testimony;

class ContactController extends Controller
{
    public function actionIndex()
    {
        $model = new Visitor();
        $model->category = 'inquiry';
        $model->slug = md5(rand());

        // get centers
        $centers = Center::find()->orderBy(['id' => SORT_DESC])->all();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['success', 'slug' => $model->slug]);
            } else {
                foreach ($model->getErrors() as $error) {
                    $message = $error[0];
                }
                Yii::$app->session->setFlash('danger', $message);
                return $this->redirect(['index']);
            }
        } else {
	        return $this->render('index', [
                'model' => $model,
                'centers' => $centers,
	        ]);
        }
    }

    public function actionSuccess($slug) {
    	$model = Visitor::find()->where(['slug' => $slug])->one();
		if (!$model) {
		    throw new \yii\web\NotFoundHttpException();
		}

        return $this->render('success', [
        	'model' => $model
        ]);
    }
}
