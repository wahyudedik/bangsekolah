<?php
namespace common\helpers;

use Yii;
/**
 * Description of Notif
 *
 * @author maf
 */
class Slack {

    public static function notify($message)
    {
        $room = "random"; 
        $icon = ":smile:"; 
        $data = "payload=" . json_encode(array(         
            "channel"       =>  "#{$room}",
            "text"          =>  $message,
            "icon_emoji"    =>  $icon
        ));

        // PT. PUS
        $url = 'https://hooks.slack.com/services/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';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        if($result === false) {
            return 'Curl error: ' . curl_error($ch);
        }
        curl_close($ch);

        return 'OK';
    }
}