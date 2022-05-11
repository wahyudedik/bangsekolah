<?php
namespace frontend\controllers;

use Yii;
use common\models\Category;
use yii\web\Controller;

// use yii\helpers\Json;

class CategoryController extends Controller
{

    /*
    public function actionGetJson() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $parent_id = $parents[0];
                $out = Category::getCategoryByParent($parent_id);
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
    }
    */
}
