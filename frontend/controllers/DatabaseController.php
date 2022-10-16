<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\base\ViewNotFoundException;
use common\helpers\Slack;
use common\models\Backup;

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
        $filename = $database."_".date("Y-m-d-His").".banksekolah.gzip";
        $downloadUrl = "https://banksekolah.com/backend/web/uploads/database/$filename";

        // mysqldump
        $cmd = "mysqldump -h {$host} -u {$username} --password={$password} {$database} | gzip > {$dir}{$filename}";
        exec($cmd);

        // save to database
        $backup = new Backup();
        $backup->filename = $filename;
        $backup->save();

        // send to telegram
        $message = "Backup database success.\nDownload here: <$downloadUrl|$filename>";
        Slack::notify($message, $filename);

        return 'Database backup success<br>Filename: '.$filename;
    }
}