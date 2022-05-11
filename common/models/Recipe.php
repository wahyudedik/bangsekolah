<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "recipe".
 *
 * @property int $id
 * @property int $product_id
 * @property string $name
 * @property string $image
 * @property string $description
 * @property int $expected_quantity
 * @property string $status
 * @property string $created_at
 * @property int $created_by
 *
 * @property Production[] $productions
 * @property Product $product
 */
class Recipe extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'recipe';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                 'class' => 'yii\behaviors\TimestampBehavior',
                 'attributes' => [
                     \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
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
            [['product_id', 'created_by'], 'integer'],
            [['description'], 'string'],
            [['expected_quantity'], 'integer'],
            [['created_at'], 'safe'],
            [['name', 'image', 'status'], 'string', 'max' => 255],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'name' => 'Recipe Name',
            'image' => 'Image',
            'expected_quantity' => 'Expected Quantity',
            'description' => 'Standard Operation Procedure',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductions()
    {
        return $this->hasMany(Production::className(), ['recipe_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getRecipeDetails()
    {
        return $this->hasMany(RecipeDetail::className(), ['recipe_id' => 'id']);
    }

}
