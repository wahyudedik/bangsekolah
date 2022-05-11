<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "country".
 *
 * @property string $iso
 * @property string $name
 * @property string $printable_name
 * @property string $iso3
 * @property int $numcode
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['iso', 'name', 'printable_name'], 'required'],
            [['numcode'], 'integer'],
            [['iso'], 'string', 'max' => 2],
            [['name', 'printable_name'], 'string', 'max' => 80],
            [['iso3'], 'string', 'max' => 3],
            [['iso'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iso' => 'Iso',
            'name' => 'Name',
            'printable_name' => 'Printable Name',
            'iso3' => 'Iso3',
            'numcode' => 'Numcode',
        ];
    }

    public static function getList()
    {
        return \yii\helpers\ArrayHelper::map(
            Country::find()->asArray()->all(),
            'name', 'name'
        );
    }
}
