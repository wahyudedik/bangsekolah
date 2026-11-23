<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\base\ViewNotFoundException;
use common\helpers\Slack;
use common\models\Backup;

class DatabaseController extends Controller
{
    /**
     * Hanya user yang sudah login (dan ber-role admin bila ada) yang boleh mengakses.
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            $user = Yii::$app->user->identity;
                            if ($user === null) {
                                return false;
                            }
                            if (method_exists($user, 'hasRole')) {
                                return $user->hasRole('admin');
                            }
                            return true;
                        },
                    ],
                ],
            ],
        ];
    }

    public function actionMysqldump()
    {
        // Guard tambahan: tolak akses kecuali admin terautentikasi.
        if (Yii::$app->user->isGuest) {
            throw new \yii\web\ForbiddenHttpException('Disabled');
        }
        $identity = Yii::$app->user->identity;
        if (method_exists($identity, 'hasRole') && !$identity->hasRole('admin')) {
            throw new \yii\web\ForbiddenHttpException('Disabled');
        }

        // vars
        $host = "localhost";
        $username = "banksekolah";
        $password = "BanksekolahDB123";
        $database = "banksekolah";
        $dir = Yii::getAlias("@uploads/database/");
        $filename = $database."_".date("Y-m-d-His").".bs.gzip";
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