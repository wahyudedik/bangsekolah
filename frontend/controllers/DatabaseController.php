<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\base\ViewNotFoundException;
use common\helpers\Slack;

class DatabaseController extends Controller
{
    public function actionMysqldump()
    {
        // vars
        $host = "localhost";
        $username = "banksekolah";
        $password = "BanksekolahDB123";
        $database = "banksekolah";
        $dir = Yii::getAlias("@uploads/database/");
        $filename = $database."_".date("Y-m-d-His").".sql.gzip";

        // mysqldump
        $cmd = "mysqldump -h {$db_host} -u {$db_username} --password={$db_password} {$db_database} | gzip > {$dir}{$filename}";
        exec($cmd);

        // send to telegram
        $message = "Backup database success.\nDownload here: <https://banksekolah.com/backend/web/uploads/database/$filename|$filename>";
        Slack::notify($message, $filename);

        return 'Database backup success<br>Filename: '.$filename;
    }
}