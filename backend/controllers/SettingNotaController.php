<?php

namespace backend\controllers;
use Yii;
use common\models\UserSchool;
use common\models\School;

class SettingNotaController extends \yii\web\Controller
{
    public function actionIndex()
    {
        \common\helpers\Sidebar::setActiveMenu('setting-nota', 'index');

        $user = Yii::$app->user->identity;
        $userSchool = UserSchool::find()->where(['user_id' => $user->id])->one();
        $school_id = null;
        if ($userSchool) $school_id = $userSchool->school_id;

        $model = School::findOne($school_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('index', [
            'model' => $model
        ]);
    }

}
