<?php
namespace common\helpers;

use Yii;

/**
 * Description of Mail
 *
 * @author maf
 */
class KirimEmail {

    public static function createList($name)
    {
        $time = time();
        $generated_token = hash_hmac("sha256","thesmedia"."::"."lLYIWteF9yS51XHOxGoiaJAwcpuP73ZQthesmedia"."::".$time,"lLYIWteF9yS51XHOxGoiaJAwcpuP73ZQthesmedia");

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.kirim.email/v3/list',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'name='.$name,
            CURLOPT_HTTPHEADER => array(
                'Auth-Id: thesmedia',
                'Auth-Token: '.$generated_token,
                'Timestamp: '.time(),
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));
        $response = curl_exec($curl);
        $arr = json_decode($response);

        curl_close($curl);
        return $arr->data->id;
    }

    public static function deleteList($id)
    {
        $time = time();
        $generated_token = hash_hmac("sha256","thesmedia"."::"."lLYIWteF9yS51XHOxGoiaJAwcpuP73ZQthesmedia"."::".$time,"lLYIWteF9yS51XHOxGoiaJAwcpuP73ZQthesmedia");

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.kirim.email/v3/list/'.$id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HTTPHEADER => array(
                'Auth-Id: thesmedia',
                'Auth-Token: '.$generated_token,
                'Timestamp: '.time(),
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));
        $response = curl_exec($curl);
        $arr = json_decode($response);
        curl_close($curl);

        return $arr->data->id;
    }

    public static function createSubscriber($idList, $email)
    {
        $time = time();
        $generated_token = hash_hmac("sha256","thesmedia"."::"."lLYIWteF9yS51XHOxGoiaJAwcpuP73ZQthesmedia"."::".$time,"lLYIWteF9yS51XHOxGoiaJAwcpuP73ZQthesmedia");

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.kirim.email/v3/subscriber',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'lists='.$idList.'&email='.$email,
            CURLOPT_HTTPHEADER => array(
                'Auth-Id: thesmedia',
                'Auth-Token: '.$generated_token,
                'Timestamp: '.time(),
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    public static function broadcast($data)
    {
        $time = time();
        $generated_token = hash_hmac("sha256","thesmedia"."::"."lLYIWteF9yS51XHOxGoiaJAwcpuP73ZQthesmedia"."::".$time,"lLYIWteF9yS51XHOxGoiaJAwcpuP73ZQthesmedia");

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.kirim.email/v3/broadcast',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Auth-Id: thesmedia',
                'Auth-Token: '.$generated_token,
                'Timestamp: '.time(),
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }	
}