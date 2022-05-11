<?php

namespace common\models;

use Yii;
use yii\helpers\Html;
use common\helpers\Strings;
use common\models\StockOpnameDetail;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property int $product_category_id
 * @property string $code
 * @property string $name
 * @property string $description
 * @property string $unit
 * @property int $stock
 * @property int $price_purchase
 * @property int $price_sale
 * @property string $status ACTIVE, NONACTIVE
 * @property string $created_at
 * @property string $updated_at
 * @property int $created_by
 *
 * @property OrderDetail[] $orderDetails
 * @property ProductCategory $productCategory
 * @property Production[] $productions
 * @property PurchaseOrderDetail[] $purchaseOrderDetails
 * @property Recipe[] $recipes
 * @property RecipeDetail[] $recipeDetails
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * Author: MAF
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                 'class' => 'yii\behaviors\TimestampBehavior',
                 'attributes' => [
                     \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                     \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                 ],
                 'value' => new \yii\db\Expression('NOW()'),
             ],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_category_id', 'name', 'code', 'price_sale'], 'required'],
            [['product_category_id', 'stock', 'price_sale', 'created_by'], 'integer'],
            [['description'], 'string'],
            [['code'], 'unique'],
            [['created_at', 'updated_at'], 'safe'],
            [['code', 'name', 'status', 'sale_status', 'unit'], 'string', 'max' => 255],
            [['product_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductCategory::className(), 'targetAttribute' => ['product_category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_category_id' => 'Product Category',
            'code' => 'Code',
            'name' => 'Product Name',
            'description' => 'Note',
            'stock' => 'Stock',
            'price_sale' => 'Price Sale',
            'status' => 'Status',
            'sale_status' => 'Sale Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            
            'smallImage' => 'Image',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderDetails()
    {
        return $this->hasMany(OrderDetail::className(), ['product_id' => 'id']);
    }

    public function getItems()
    {
        return $this->hasMany(Item::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCategory()
    {
        return $this->hasOne(ProductCategory::className(), ['id' => 'product_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductions()
    {
        return $this->hasMany(Production::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseOrderDetails()
    {
        return $this->hasMany(PurchaseOrderDetail::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecipes()
    {
        return $this->hasMany(Recipe::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecipeDetails()
    {
        return $this->hasMany(RecipeDetail::className(), ['product_id' => 'id']);
    }

    public function getSmallImage()
    {
        return Html::img('https://www.resepistimewa.com/wp-content/uploads/resep-bakso-sapi-rumahan.jpg',
            ['class' => 'img img-rounded', 'width' => '50px']
        );
    }

    public static function getList()
    {
        return \yii\helpers\ArrayHelper::map(
            Product::find()->where(['status' => 'ACTIVE'])->asArray()->all(),
            'id', function($model) {
                // return $model['name'].' ('.$model['unit'].')';
                return $model['name'];
            }
        );
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getPriceSale()
    {
        return Strings::getRupiah($this->price_sale);
    }

    public function getLastOpnameAt()
    {
        $opname = StockOpnameDetail::find()
            ->where(['product_id' => $this->id])
            ->orderBy(['id' => SORT_DESC])
            ->limit(1)
            ->one();
        if ($opname) {
            return date('d M Y', strtotime($opname->checked_at));
        } else {
            return 'Never checked';
        }
    }
}
