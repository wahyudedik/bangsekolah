<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\imagine\Image;
use common\helpers\Mail;
use common\helpers\Slack;
use common\helpers\Strings;
use common\helpers\BasicAuth;

use common\models\User;
use common\models\Item;
use common\models\Recipe;
use common\models\Setting;
use common\models\Product;
use common\models\Production;
use common\models\RecipeDetail;
use common\models\ProductionItem;
use common\models\ProductionDetail;

use DateTime;
use DatePeriod;
use DateInterval;

class ApiProductionController extends Controller
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
        $arrProduction = [];
        
        $query = Production::find()
            ->orderBy(['id' => SORT_DESC])
            ->all();
        
        if (count($query) > 0) {
            foreach ($query as $production) {
                $arrProduction[] = [
                    'id' => $production->id,
                    'production_number' => $production->production_number,
                    'quantity' => $production->quantity.'',
                    'status' => $production->status,
                    'created_at' => date('d M Y', strtotime($production->created_at)),
                ];
            }
        } else {
            return [[
                'message' => 'Production not found'
            ]];
        }
        return $arrProduction;
    }

    public function actionCreate()
    {
        // get latest production number from setting
        $setting = Setting::find()->where(['key' => 'prod-number'])->one();
        $productionNumber = $setting->value + 1;
        $setting->value = $productionNumber.'';

        // create new production
        $production = new Production();
        $production->status = 'DRAFT';
        $production->production_number = 'PROD-'.sprintf("%05d", $productionNumber);
        $production->quantity = 0;
        $production->created_by = BasicAuth::user()->id;

        if ($production->save() ) {

            // save added production number in setting
            $setting->save();
            return $production;
        } else {
            foreach ($production->errors as $error) {
                throw new \yii\web\ServerErrorHttpException($error[0]);
            }
        }
    }

    public function actionUpdate($id)
    {

        //create production
        $production = Production::find()->where(['production_number' => $id])->one();
        $production->status = Yii::$app->request->post('status');
        $production->recipe_id = Yii::$app->request->post('recipe_id');
        $production->product_id = Yii::$app->request->post('product_id');
        $production->serving = Yii::$app->request->post('serving');

        if ($production->save() ) {

            // reset all production_detail records
            ProductionDetail::deleteAll(['production_id' => $production->id]);

            // copy recipe_detail to production_detail
            foreach ($production->recipe->recipeDetails as $recipeDetail) {
                $productionDetail = new ProductionDetail();
                $productionDetail->production_id = $production->id;
                $productionDetail->product_id = $recipeDetail->product_id;
                $productionDetail->amount = ($recipeDetail->amount * $production->serving);
                $productionDetail->unit = $recipeDetail->unit;
                $productionDetail->created_by = $recipeDetail->created_by;
                if (!$productionDetail->save()) {
                    foreach ($productionDetail->errors as $error) {
                        return $error[0];
                    }
                }
            }

            return $production;
        } else {
            foreach ($production->errors as $error) {
                throw new \yii\web\ServerErrorHttpException($error[0]);
            }
        }
    }

    public function actionView($id = null)
    {
        $production = Production::findOne($id);
        // $production = Production::find()->where(['production_number' => $id])->one();
        if (!$production) {
            $response = [
                'message' => 'Production not found'
            ];
            return $response;
        }
        
        $arrProduction = [
            'id' => $production->id,
            'production_number' => $production->production_number,
            'recipe' => $production->recipe ? $production->recipe->name : '',
            'description' => $production->recipe ? $production->recipe->description : '',
            'product' => $production->product ? $production->product->name : '',
            'serving' => $production->serving.'',
            'expected_quantity' => ($production->serving * ($production->recipe ? $production->recipe->expected_quantity : 0)).'',
            'quantity' => $production->quantity.'',
            'status' => $production->status,
            'note' => $production->note.'',
            'created_by' => $production->createdBy->name,
            'created_at' => date('d M Y', strtotime($production->created_at)),
            'complete_status' => $production->completeStatus,
        ];

        return $arrProduction;
    }

    public function actionItems($id = null)
    {
        $query = Item::find()->where(['production_id' => $id])->all();
        
        if (count($query) > 0) {
            foreach ($query as $item) {
                $arrItem[] = [
                    'code' => $item->code,
                    'name' => $item->name,
                    'base_amount' => $item->base_amount.'',
                    'unit' => $item->unit,
                    'price_purchase' => Strings::getRupiah($item->price_purchase),
                    'price_sale' => Strings::getRupiah($item->product->price_sale),
                    'expired_date' => date('d M Y', strtotime($item->expired_date)),
                ];
            }
        } else {
            $arrItem[] = [
                'message' => 'Item production not found'
            ];
        }
        return $arrItem;
    }

    public function actionScanItem($tampilan = 'SEMUA', $id = '', $idproduct = '')
    {
        //$prod = Production::findOne($id);
        $query = ProductionItem::find()
            ->where(['production_id' => $id])
            ->andWhere(['product_id' => $idproduct])
            ->orderBy(['id' => SORT_DESC])
            ->all();
        
        if (count($query) > 0) {
            foreach ($query as $productionItem) {
                $arrProduction[] = [
                    'id' => $productionItem->id,
                    'product' => $productionItem->product->name,
                    'item_code' => $productionItem->item->code,
                    'amount' => $productionItem->amount,
                    'unit' => $productionItem->unit,
                    'exp_date' => date('d M Y', strtotime($productionItem->item->expired_date)),
                    'purchase_price' => Strings::getRupiah(($productionItem->amount/$productionItem->item->base_amount)*$productionItem->item->price_purchase),
                ];
            }
        } else {
            $arrProduction[] = [
                'message' => 'Item not found'
            ];
        }
        return $arrProduction;
    }

    public function actionAddItem()
    {
        //check quantity in recipe
        $production = Production::findOne(Yii::$app->request->post('production_id'));

        // update scanned item to NONACTIVE
        $item_code = Yii::$app->request->post('item_code');
        $item = Item::find()->where(['code' => $item_code, 'status' => 'ACTIVE'])->one();
        if (!$item) {
            return [
                'message' => 'Product with code '.$item_code.' is not found'
            ];
        }

        // decrease product stock
        $product = Product::find()->where(['code' => $item->product_code])->one();
        $stock = $product->stock;

        $recipeDetail = RecipeDetail::find()
            ->where(['recipe_id' => $production->recipe_id])
            ->andWhere(['product_id' => $item->product_id])
            ->one();

        $productionDetail = ProductionDetail::find()
            ->where(['production_id' => $production->id])
            ->andWhere(['product_id' => $item->product_id])
            ->one();
        if($productionDetail->product_id != Yii::$app->request->post('product_id')){
            return [
                'message' => 'The item unit does not match the product'
            ];
        }
        if ($productionDetail->unit != $item->unit) {
            return [
                'message' => 'The item unit is more than required'
                //'message' => 'production amount = '.$productionDetail->amount.' production unit '.$productionDetail->unit.' recipe unit '.$recipeDetail->unit
            ];
        } else {
            //checking amount
            $checkAmounts = ProductionItem::find()
            ->where(['production_id' => $production->id])
            ->andWhere(['product_id' => $item->product_id])
            ->all();
            $countAmount = 0;
            foreach ($checkAmounts as $checkAmount) {
                $countAmount += $checkAmount->amount;
            }
            if($countAmount >= $productionDetail->amount){
                return [
                    'message' => 'This amount exceeds the specified amount'
                ];
            }
            $decrease_amount_item = 0;
            if(($productionDetail->amount - $countAmount) > $item->amount) {
                $decrease_amount_item = $item->amount;
            } else {
                $decrease_amount_item = $productionDetail->amount - $countAmount;
            }

            //$decrease_amount_item = $productionDetail->amount - $countAmount;

            $productionItem = new ProductionItem();
            $productionItem->production_id = Yii::$app->request->post('production_id');
            $productionItem->item_id = $item->id;
            $productionItem->product_id = $product->id;
            $productionItem->amount = $decrease_amount_item;
            //$productionItem->amount = $productionDetail->amount > $item->amount ? $item->amount : $productionDetail->amount;
            $productionItem->unit = $item->unit;
            $productionItem->created_by = BasicAuth::user()->id;

            $checkItem = ProductionItem::find()
            ->where(['item_id' => $item->id])
            ->andWhere(['production_id' => $production->id])
            ->one();
            if ($checkItem) {
                return [
                    'message' => 'This item is already in the list'
                ];
            }

            if ($productionItem->save() ) {
                //$item->status = 'NONACTIVE';
                $amount_decrease = $item->amount - $decrease_amount_item;
                $item->amount = $item->amount - $decrease_amount_item;
                $item->status = $amount_decrease <= 0 ? 'NONACTIVE' : 'ACTIVE';
                if ($item->save()) {
                    // decrease product stock if amount <= nol
                    if($item->amount <= 0) {
                        $product->stock = $stock - 1;
                        if ($product->save()) {
                            return $productionItem;
                        } else {
                            return [
                                'message' => 'Product stock failed to save'
                            ];
                        }
                    } else {
                        return $productionItem;
                    }
                } else {
                    return [
                        'message' => 'Item failed to save'
                    ];
                }
            } else {
                foreach ($production->errors as $error) {
                    throw new \yii\web\ServerErrorHttpException($error[0]);
                }
            }

        }
    }

    public function actionDeleteItem($id = null)
    {
        if (!$id) {
            throw new \yii\web\BadRequestHttpException('Parameter tidak lengkap');
        }
        $query = ProductionItem::findOne($id);
        $item_id = $query->item_id;
        $amount = $query->amount;

        if ($query->delete()) {
            $item = Item::findOne($item_id);
            $first_amount = $item->amount;
            $item->amount = $item->amount + $amount;
            $item->status = 'ACTIVE';
            $item->save();
            if($first_amount == 0){
                $product = Product::find()->where(['code' => $item->product_code])->one();
                $product->stock = $product->stock + 1;
                $product->save();
            }
            //return $item->status;
            
        }

        return ['message' => 'Item successfully canceled'];
    }

    public function actionFinish($id)
    {

        // update production to FINISHED
        $production = Production::findOne($id);
        if (!$production) {
            $response = [
                'message' => 'Production is not found'
            ];
            return $response;
        }
        $product = $production->product;

        $production->status = 'FINISHED';
        $production->quantity = Yii::$app->request->post('quantity');
        $production->note = Yii::$app->request->post('note_production');
        
        // determine product price_purchase
        // from HPP
        $productionItemCost = intval(round($production->productionCost/$production->quantity));

        if ($production->save() ) {
            
            // insert item as a result of production process
            for ($i=0; $i < $production->quantity; $i++) { 
                //input table Item
                $date = strtotime(date("Y-m-d h:i:sa"));
                $item = new Item();
                $item->production_id = $production->id;
                $item->product_id = $production->product_id;
                $item->product_code = $production->product->code;
                $item->code = $production->product->code.'-'.strtotime(date("Y-m-d h:i:sa")).$i.'';
                $item->name = $production->product->name;
                $item->unit = $production->product->unit;
                $item->base_amount = 1;
                $item->amount = 1;
                $item->price_purchase = $productionItemCost;
                $item->expired_date = date("Y-m-d", strtotime("+3 month", $date));;
                $item->status = 'ACTIVE';
                $item->created_by = BasicAuth::user()->id;
                if (!$item->save()) {
                    foreach ($item->errors as $error) {
                        return $error[0];
                    }
                }
            }

            //update stock product
            $product->stock = $product->stock + $production->quantity;
            $product->save();

            // Slack notification
            // Emoji: https://projects.iamcal.com/emoji-data/table.htm
            $content = ":shopping_trolley: *NEW PRODUCTION BATCH FINISHED*\n";
            $content .= "\n*PRODUCTION DETAIL:*\n";
            $content .= "Production number = ".$production->production_number."\n";
            $content .= "Production Date = ".date('d M Y', strtotime($production->created_at))."\n";
            $content .= "Produced By = ".$production->createdBy->name."\n";
            $content .= "Recipe Name = ".$production->recipe->name."\n";
            $content .= "Product output = ".$production->product->name."\n";
            $content .= "Number of serving = ".$production->serving."\n";
            $content .= "Expected quantity = ".$production->recipe->expected_quantity."\n";
            $content .= "Result quantity = ".$production->quantity."\n";
            $content .= "Production cost = ".Strings::getRupiah($production->productionCost)."\n";
            $content .= "Item cost estimation = ".Strings::getRupiah(($production->productionCost/$production->quantity))."\n";
            $content .= "Chef Note = ".$production->note."\n\n";
            $content .= "Please make sure each produced item has been given a barcode so that the item is registered in the database. Thank you :blush:.";
            Slack::notify($content);

            /**
             * Send email notification to Admin
             * Using KIRIM.EMAIL API
             */
            $from = 'noreply@pt-pus.com';
            $to = Setting::find()->where(['key' => 'admin-email'])->one()->value;
            $subject = 'PT. PUS - Production '.$production->product->name.' Finished';
            $content = "Dear Admin, <br><br><strong>New production batch number ".$production->production_number." for ".$production->product->name." is finished. This is production detail.</strong><br>";
            $content .= "<br><strong>PRODUCTION DETAIL:</strong><br>";
            $content .= "Production number = ".$production->production_number."<br>";
            $content .= "Production Date = ".date('d M Y', strtotime($production->created_at))."<br>";
            $content .= "Produced By = ".$production->createdBy->name."<br>";
            $content .= "Recipe Name = ".$production->recipe->name."<br>";
            $content .= "Product output = ".$production->product->name."<br>";
            $content .= "Number of serving = ".$production->serving."<br>";
            $content .= "Expected quantity = ".$production->recipe->expected_quantity."<br>";
            $content .= "Result quantity = ".$production->quantity."<br>";
            $content .= "Production cost = ".Strings::getRupiah($production->productionCost)."<br>";
            $content .= "Item cost estimation = ".Strings::getRupiah(($production->productionCost/$production->quantity))."<br>";
            $content .= "Chef Note = ".$production->note."<br><br>";
            $content .= "Please make sure each produced item has been given a barcode so that the item is registered in the database. Thank you.";
            Mail::send($from, $to, $subject, $content);

            return $production;
        } else {
            foreach ($production->errors as $error) {
                throw new \yii\web\ServerErrorHttpException($error[0]);
            }
        }
    }

    public function actionIngredients($id = null)
    {
        //return Product::findOne($id);
        $productionDetails = ProductionDetail::find()->where(['production_id' => $id])->all();
        
        if (count($productionDetails) > 0) {
            foreach ($productionDetails as $productionDetail) {
                $arrIngredients[] = [
                    'product_id' => $productionDetail->product_id,
                    'product' => $productionDetail->product->name,
                    'quantity' => $productionDetail->amount.'',
                    'unit' => $productionDetail->unit,
                    'complete_status' => $productionDetail->completeStatus,
                ];
            }
        } else {
            $arrIngredients[] = [
                'message' => 'Ingredients / raw material is not found'
            ];
        }
        return $arrIngredients;
    }

    public function actionDelete($id = null)
    {
        if (!$id) {
            throw new \yii\web\BadRequestHttpException('Missing parameters');
        }
        $production = Production::findOne($id);

        if ($production->delete()) {
            return ['message' => 'Production successfully deleted'];
        } else {
            return ['message' => 'Production failed to delete'];
        }
        
    }

}
