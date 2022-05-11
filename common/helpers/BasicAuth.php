<?php
namespace common\helpers;
use Yii;
use common\models\User;

/**
 * Description of Auth
 *
 * @author maf
 */
class BasicAuth {

    public static function user() {
    	$authHeader = Yii::$app->request->headers['authorization'];
        $explodedHeader = explode(' ', $authHeader);
        $base64 = $explodedHeader[1];
        $plainAuth = base64_decode($base64);
        $explodedAuth = explode(':', $plainAuth);
        $username = $explodedAuth[0];
        $password = $explodedAuth[1];

        // get User
        $user = User::find()->where(['username' => $username])->one();
        if ($user) {
	    	if (Yii::$app->security->validatePassword($password, $user->password_hash)) {
				Yii::$app->session->set('id', $user->id);
        		return $user;
	    	} else {
	    		return null;
	    	}
        } else {
        	return null;
        }
    }


}