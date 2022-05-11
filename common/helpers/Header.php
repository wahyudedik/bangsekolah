<?php
namespace common\helpers;

use Yii;
use common\models\Media;

/**
 * Description of String
 *
 * @author maf
 */
class Header {

    public static function getUrl($slug) {
        $media = Media::find()->where(['slug' => $slug])->one();
        if ($media) {
            return $media->url;
        } else {
            return Yii::getAlias('@fronturl/uploads/media/default-image.jpg');
        }
    }

}
