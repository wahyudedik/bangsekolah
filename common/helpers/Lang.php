<?php
namespace common\helpers;

use Yii;
use common\models\Language;

/**
 * Description of Lang
 *
 * @author maf
 */
class Lang {

    public static function text($slug, $default = null)
    {
    	// get choosen localization
		$cookies = Yii::$app->request->cookies;
		$lang = $cookies->getValue('language', 'id');

		// get text from database
		$language = Language::find()->where(['slug' => $slug])->one();
		if ($language) {
			return $language[$lang.'_content'];
		} else {
			if ($default) {
				return $default;
			} else {
				return '[translation not found]';
			}
		}
    }

}