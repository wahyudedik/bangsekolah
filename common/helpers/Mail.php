<?php
namespace common\helpers;

use Yii;

/**
 * Description of Mail
 *
 * @author maf
 */
class Mail {

    public static function send($from, $to, $subject, $text)
    {
        $params = ['body' => $text];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://aplikasi.kirim.email/u0/api/v3/transactional/messages');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $post = array(
            'from' => $from,
            'to' => $to,
            'subject' => $subject,
            'text' => $text,
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_USERPWD, 'api' . ':' . 'dabd552cbf0b0c64dbd1cb8890a173b60d7422909e6e2e6301c42effe82c053e');
        $headers = array();
        $headers[] = 'Domain: pt-pus.com';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $sent = curl_exec($ch);
        if (curl_errno($ch)) {
            $sent = false;
        }
        curl_close($ch);

        return 'OK';
    }	

}