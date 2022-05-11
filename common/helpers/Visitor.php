<?php
namespace common\helpers;

use Yii;

/**
 * Description of String
 *
 * @author maf
 */
class Visitor {

    public static function getBrowser() {
        $arrBrowsers = ['Opera', 'Edg', 'Chrome', 'Safari', 'Firefox', 'MSIE', 'Trident'];
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $clientBrowser = '';
        foreach ($arrBrowsers as $browser) {
            if (strpos($agent, $browser) !== false) {
                $clientBrowser = $browser;
                break;
            }
        }
        return $clientBrowser;
    }

}
