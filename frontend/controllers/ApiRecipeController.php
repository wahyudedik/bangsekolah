<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\imagine\Image;
use common\helpers\BasicAuth;
use common\helpers\Strings;
use common\models\User;
use common\models\Recipe;
use common\models\RecipeDetail;
use DateTime;
use DatePeriod;
use DateInterval;

class ApiRecipeController extends Controller
{

    public function beforeAction($action)
    {
        // disable csrf and set response format JSON
        $this->enableCsrfValidation = false;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // check BasicAuth
        $user = BasicAuth::user();
        if ($user == null) throw new \yii\web\UnauthorizedHttpException('Unauthorized access');
        
        // if everything is going well
        return parent::beforeAction($action);
    }

    public function actionIndex($tampilan = 'SEMUA', $name = '')
    {
        $query = Recipe::find()
            ->where(['status' => 'ACTIVE'])
            ->andWhere(['like', 'name', $name])
            ->orderBy(['id' => SORT_DESC])
            ->all();
        
        if (count($query) > 0) {
            foreach ($query as $recipe) {
                $arrRecipe[] = [
                    'id' => $recipe->id,
                    'product_id' => $recipe->product_id,
                    'product' => $recipe->product->name,
                    'name' => $recipe->name,

                    //'status' => $product->status_aktif,
                ];
            }
        } else {
            $arrRecipe[] = [
                'message' => 'Recipe not found'
            ];
        }
        return $arrRecipe;
    }

    public function actionView($id = null)
    {
        //return Product::findOne($id);
        $recipe = Recipe::findOne($id);
        
        $arrRecipe = [
            'id' => $recipe->id,
            'product' => $recipe->product->name,
            'name' => $recipe->name,
            'description' => $recipe->description,
            'expected_quantity' => $recipe->expected_quantity.'',
        ];

        return $arrRecipe;
    }

    public function actionIngredients($id = null)
    {
        //return Product::findOne($id);
        $recipeDetails = RecipeDetail::find()->where(['recipe_id' => $id])->all();
        
        if (count($recipeDetails) > 0) {
            foreach ($recipeDetails as $recipeDetail) {
                $arrRecipeDetail[] = [
                    'product_id' => $recipeDetail->product_id,
                    'product' => $recipeDetail->product->name,
                    'amount' => $recipeDetail->amount.'',
                    'unit' => $recipeDetail->unit,
                    'note' => $recipeDetail->note,
                ];
            }
        } else {
            $arrRecipeDetail[] = [
                'message' => 'Ingredients not found'
            ];
        }
        return $arrRecipeDetail;
    }

}
