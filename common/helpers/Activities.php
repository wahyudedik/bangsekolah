<?php
namespace common\helpers;

use Yii;
use common\models\Activity;
use common\models\UserSchool;

/**
 * Description of String
 *
 * @author maf
 */
class Activities {

    public static function log($name, $description = null) {
        // activity log
        $user = Yii::$app->user->identity;
        $userSchool = UserSchool::find()->where(['user_id' => $user->id])->one();
        $school_id = null;
        if ($userSchool) $school_id = $userSchool->school_id;
        $activity = new Activity();
        $activity->user_id = Yii::$app->user->identity->id;
        $activity->school_id = $school_id;
        $activity->name = $name;
        $activity->description = $description;
        $activity->save();

        return 'OK';
    }

}
