<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\helpers\Mail;
use common\helpers\KirimEmail;
use common\models\Subscriber;
use yii\mailchimp\MailchimpMarketing;
use Guzzle\Http\Exception\ClientErrorResponseException;

class SubscribeController extends Controller
{
    public function actionJoin()
    {
        $model = new Subscriber();
        if ($model->load(Yii::$app->request->post())) {
            $model->email = strtolower($model->email);
            if ($model->save()) {
                $cookies = Yii::$app->response->cookies;
                $cookies->add(new \yii\web\Cookie([
                    'name' => 'modal-subscriber',
                    'value' => 'true',
                ]));

                // register to KirimEmail
                $response = KirimEmail::createSubscriber('180813', $model->email);
                $data = json_decode($response);
                if ($data->code == 200) {
                    return $this->redirect(['success', 'email' => $model->email, 'message' => 'SUCCESS']);
                } else {
                    return $this->redirect(['success', 'email' => $model->email, 'message' => 'FAIL']);
                }
            } else {
                return $this->redirect(['success', 'email' => $model->email, 'message' => 'FAIL']);
            }
        } else {
            return $this->redirect(['site/index']);
        }
    }

    public function actionForm() {
        $model = new Subscriber();
        if ($model->load(Yii::$app->request->post())) {
            $model->email = strtolower($model->email);

            if ($model->save()) {
                // register to KirimEmail
                $response = KirimEmail::createSubscriber('180813', $model->email);
                $data = json_decode($response);
                if ($data->code == 200) {
                    return $this->redirect(['success', 'email' => $model->email, 'message' => 'SUCCESS']);
                } else {
                    return $this->redirect(['success', 'email' => $model->email, 'message' => 'FAIL']);
                }
            } else {
                return $this->redirect(['success', 'email' => $model->email, 'message' => 'FAIL']);
            }
        }
        return $this->render('form', [
            'model' => $model
        ]);
    }

    public function actionSuccess($email) {
        return $this->render('success', [
        	'email' => $email
        ]);
    }

    public function actionError() {
        return $this->render('error');
    }

    public function actionMailchimp($email = 'muhammadarieffakhrudin@gmail.com') {
        // api_key: e2a2f297ec17efd1ddeddebb6098227d-us5
        // audience_id: c41ea9b33f
        // server: us5
        // $mailchimp = new MailchimpMarketing();
        $mailchimp = new \MailchimpMarketing\ApiClient();

        $mailchimp->setConfig([
            'apiKey' => 'e2a2f297ec17efd1ddeddebb6098227d-us5',
            'server' => 'us5'
        ]);
        $list_id = 'c41ea9b33f';
        try {
            $response = $mailchimp->lists->addListMember($list_id, [
                'email_address' => 'muhammadarieffakhrudin@outlook.com',
                'status' => 'pending',
                'merge_fields' => [
                    'FNAME' => 'Arief',
                    'LNAME' => 'Fakhrudin'
                ]
            ]);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            /**
             * Here we actually catch the instance of GuzzleHttp\Psr7\Response
             * (find it in ./vendor/guzzlehttp/psr7/src/Response.php) with all
             * its own and its 'Message' trait's methods. See more explanations below.
             *
             * So you can have: HTTP status code, message, headers and body.
             * Just check the exception object has the response before.
             */
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $body = json_decode((string) $response->getBody());
                return $body->title;
            }
        }
    }

    public function actionSendWelcome()
    {

                // send email notification
                // $to = $model->email;
                // $subject = 'THE S MEDIA';
                // $textBody = 'Subscribe Success';
                // $params = ['email' => $model->email];
                // if (!in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
                //     $sent = Mail::send('noreplyEmail', $to, $subject, $textBody, 'subscribe-email', $params);
                // } else {
                //     $sent = true;
                // }
                // if ($sent) {
                //     return $this->redirect(['success', 'email' => $model->email, 'message' => 'SUCCESS']);
                // } else {
                //     return $this->redirect(['success', 'email' => $model->email, 'message' => 'FAIL']);
                // }
    }

}
