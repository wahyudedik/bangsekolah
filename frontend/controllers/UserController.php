<?php
namespace frontend\controllers;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\imagine\Image;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;
use common\helpers\Mail;
use common\helpers\Strings;

use common\models\User;
use common\models\UserProfile;
use common\models\UserExperience;
use common\models\UserTraining;

class UserController extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        parent::beforeAction($action);
        $requireCheck = true;
        switch (Yii::$app->controller->action->id) {
            case 'register':
            case 'login':
            case 'confirmation':
            case 'email-activation':
            case 'forgot-password':
            case 'reset-password':
            case 'reset-password-form':
            case 'send-reset-password-email':
            case 'send-activation-email':
                $requireCheck = false;
                break;
        }
        if ($requireCheck) {
            if (!$this->currentUser()) {
                Yii::$app->session->setFlash('danger', 'Silakan login terlebih dahulu.');
                $this->redirect(['user/login']);
                return false;
            }
        }
        return true;
    }

    public function actionIndex()
    {
        $model = $this->currentUser();
        if (!$model) {
            return $this->redirect(['user/login']);
        }
        return $this->redirect(['user/profile']);
    }

    public function actionRegister()
    {
        $model = new User();
        $model->role_id = 3;
        $model->status = 0;
        $model->mode = 'register';

        if ($model->load(Yii::$app->request->post())) {

            // google recaptcha validation
            $captcha = Yii::$app->request->post('g-recaptcha-response');
            if(!$captcha){
                Yii::$app->session->setFlash('danger', 'Captcha is required');
                return $this->redirect(['user/register']);
            }
            $secretKey = '6Lcm6MIZAAAAAFlHpXI0GAE4SY_gSHo3PjQNLj1e';
            $url = 'https://www.google.com/recaptcha/api/siteverify?secret='.urlencode($secretKey).'&response='.urlencode($captcha);
            $response = file_get_contents($url);
            $responseKeys = json_decode($response,true);
            if(!$responseKeys['success']) {
                Yii::$app->session->setFlash('danger', 'Invalid captcha');
                return $this->redirect(['user/register']);
            }

            $model->email = strtolower($model->email);
            $model->password_hash = Yii::$app->security->generatePasswordHash($model->password);
            $model->username = md5(rand());
            if ($model->save()) {
                /**
                 * Mengirim email konfirmasi
                 */
                $to = $model->email;
                $subject = 'Vista Education';
                $textBody = 'Email Confirmation';
                $body = 'Dear '.$model->name.', this email is registered to Vista Education. If you feel use this email, please confirm with the link below:<br>';
                $body .= Html::a('Click to confirm', Yii::getAlias('@fronturl') . '/user/confirmation?email=' . urlencode($model->email) . '&token=' . md5($model->id));
                $body .= '<br><br><br>Thank you,<br>';
                $body .= 'Vista Education';
                $params = ['body' => $body];
                $sent = Mail::send('noreplyEmail', $to, $subject, $textBody, 'notification-mail', $params);
                if ($sent) {
                    Yii::$app->session->setFlash('success', 'Confirmation link has been sent to your email. Check your inbox or spam folder.');
                } else {
                    Yii::$app->session->setFlash('danger', 'Confirmation link failed to sent to your email');
                }

                return $this->redirect(['user/login']);
            } else {
                foreach ($model->errors as $error) {
                    Yii::$app->session->setFlash('danger', $error[0]);
                }
                return $this->redirect(['user/register']);
            }
        } else {
            return $this->render('register', [
                'model' => $model,
            ]);
        }
    }

    public function actionConfirmation($email, $token)
    {
        $model = User::find()->where(['email' => $email])->one();
        if ($model) {
            if (md5($model->id) == $token) {
                $model->status = 10;
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Email successfully confirmed. Use your email when login.');
                } else {
                    foreach ($model->errors as $error) {
                        Yii::$app->session->setFlash('danger', $error[0]);
                    }
                }
            } else {
                Yii::$app->session->setFlash('danger', 'Your email cannot be confirmed, wrong token code. Make sure to click your confirmation link in your email.');
            }
        } else {
            Yii::$app->session->setFlash('danger', 'Email is not found');
        }
        return $this->redirect(['user/login']);
    }

    public function actionLogin($referrer = null)
    {
        $model = new User();
        $model->mode = 'login';
        if ($model->load(Yii::$app->request->post())) {
            // google recaptcha validation
            $captcha = Yii::$app->request->post('g-recaptcha-response');
            if(!$captcha){
                Yii::$app->session->setFlash('danger', 'Captcha is required');
                return $this->redirect(['user/login']);
            }
            $secretKey = '6Lcm6MIZAAAAAFlHpXI0GAE4SY_gSHo3PjQNLj1e';
            $url = 'https://www.google.com/recaptcha/api/siteverify?secret='.urlencode($secretKey).'&response='.urlencode($captcha);
            $response = file_get_contents($url);
            $responseKeys = json_decode($response,true);
            if(!$responseKeys['success']) {
                Yii::$app->session->setFlash('danger', 'Invalid captcha');
                return $this->redirect(['user/login']);
            }

            $user = User::find()->where(['email' => $model->email])->one();
            if ($user) {
                if ($user->status == 10) {
                    if (Yii::$app->security->validatePassword($model->password, $user->password_hash)) {
                        Yii::$app->session->set('id', $user->id);
                        Yii::$app->session->set('name', $user->name);
                        Yii::$app->session->set('role', 'member');
                        return $this->redirect(['user/profile']);
                    } else {
                        Yii::$app->session->setFlash('danger', 'Password is wrong');
                        return $this->redirect(['user/login']);
                    }
                } else {
                    Yii::$app->session->setFlash('danger', 'Email is not confirmed. Check your inbox.');
                    return $this->redirect(['user/login']);
                }
            } else {
                Yii::$app->session->setFlash('danger', 'Email is not registered');
                return $this->redirect(['user/login']);
            }
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionProfile($tab = null)
    {
        $model = $this->currentUser();
        if ($model === null) {
            Yii::$app->session->setFlash('danger', 'Silakan login terlebih dahulu.');
            return $this->redirect(['user/login']);
        }

        // user profile
        if ($model->userProfiles) {
            foreach ($model->userProfiles as $profile) {
                $userProfile = $profile;
                break;
            }
        } else {
            $userProfile = null;
        }

        return $this->render('profile', [
            'model' => $model,
            'userProfile' => $userProfile,
            'tab' => $tab,
        ]);
    }
    
    public function actionUpdateProfile()
    {
        $user = $this->currentUser();
        if ($user === null) {
            Yii::$app->session->setFlash('danger', 'Silakan login terlebih dahulu.');
            return $this->redirect(['user/login']);
        }

        if ($user->userProfiles) {
            foreach ($user->userProfiles as $userProfile) {
                $model = $userProfile;
                break;
            }
        } else {
            $model = new UserProfile();
            $model->user_id = $user->id;
        }
        $model->name = $user->name;
        $oldImage = $user->image;
        
        if ($model->load(Yii::$app->request->post())) {
            // save user name
            $user->name = $model->name;
            $user->save();

            // upload image
            $image = UploadedFile::getInstance($model, 'image');
            if (isset($image)) {
                $exploded_image = explode('.', $image->name);
                $extension = end($exploded_image);
                if ($extension !== 'jpg' && $extension !== 'jpeg' && $extension !== 'png' && $extension !== 'JPG' && $extension !== 'JPEG' && $extension !== 'PNG') {
                    Yii::$app->session->setFlash('danger', 'Only JPG dan PNG allowed to upload');
                    return $this->redirect(['user/profile']);
                }
                $temporary_image = Yii::$app->security->generateRandomString() . ".{$extension}";
                $path = Yii::getAlias('@uploads/user/'.$temporary_image);
                $image->saveAs($path);

                /**
                 * memastikan thumbnail besar memiliki rasio 1:1 (square) dan maksimal 600x600
                 * jika ukuran kurang dari 600x600, maka dipilih ukuran terkecil agar rasionya tetap square
                 */
                $imageSize = getimagesize($path);
                if ($imageSize[0] >= 600 && $imageSize[1] >= 600) {
                    Image::thumbnail($path, 600, 600)->save(Yii::getAlias('@uploads/user/large-'.$temporary_image), ['quality' => 100]);
                } else if ($imageSize[0] <= 600 || $imageSize[1] <= 600) {
                    if ($imageSize[0] < $imageSize[1]) {
                        Image::thumbnail($path, $imageSize[0], $imageSize[0])->save(Yii::getAlias('@uploads/user/large-'.$temporary_image), ['quality' => 100]);
                    } else {
                        Image::thumbnail($path, $imageSize[1], $imageSize[1])->save(Yii::getAlias('@uploads/user/large-'.$temporary_image), ['quality' => 100]);
                    }
                }
                Image::thumbnail($path, 100, 100)->save(Yii::getAlias('@uploads/user/').'small-'.$temporary_image, ['quality' => 100]);
                @unlink($path);

                // hapus image lama
                @unlink(Yii::getAlias('@uploads/user/small-'.$oldImage));
                @unlink(Yii::getAlias('@uploads/user/large-'.$oldImage));
                /**
                 * simpan image dan password
                 */
                $user->image = $temporary_image;
                $user->save();
            }
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Profil successfully updated');
            } else {
                foreach ($model->errors as $error) {
                    Yii::$app->session->setFlash('danger', $error[0]);
                }
            }
            return $this->redirect(['user/profile']);
        } else {
            return $this->render('update-profile', [
                'model' => $model,
            ]);
        }
    }

    public function actionAddExperience()
    {
        $user = $this->currentUser();
        if ($user === null) {
            Yii::$app->session->setFlash('danger', 'Silakan login terlebih dahulu.');
            return $this->redirect(['user/login']);
        }
        $model = new UserExperience();
        $model->user_id = $user->id;
        
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Experience successfully saved');
            } else {
                foreach ($model->errors as $error) {
                    Yii::$app->session->setFlash('danger', $error[0]);
                }
            }
            return $this->redirect(['user/profile', 'tab' => 'experience']);
        } else {
            return $this->render('add-experience', [
                'model' => $model,
            ]);
        }
    }

    public function actionDeleteExperience($id)
    {
        $user = $this->currentUser();
        if ($user === null) {
            Yii::$app->session->setFlash('danger', 'Silakan login terlebih dahulu.');
            return $this->redirect(['user/login']);
        }
        $model = UserExperience::findOne($id);
        if (!$model->user_id == $user->id) {
            throw new \yii\web\ForbiddenHttpException('You are not authorized to perform this action');
        }
        if (!$model->delete()) {
            Yii::$app->session->setFlash('danger', 'Experience failed to delete');
        }
        return $this->redirect(['user/profile', 'tab' => 'experience']);
    }

    public function actionAddTraining()
    {
        $user = $this->currentUser();
        if ($user === null) {
            Yii::$app->session->setFlash('danger', 'Silakan login terlebih dahulu.');
            return $this->redirect(['user/login']);
        }
        $model = new UserTraining();
        $model->user_id = $user->id;
        
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Training successfully saved');
            } else {
                foreach ($model->errors as $error) {
                    Yii::$app->session->setFlash('danger', $error[0]);
                }
            }
            return $this->redirect(['user/profile', 'tab' => 'training']);
        } else {
            return $this->render('add-training', [
                'model' => $model,
            ]);
        }
    }

    public function actionDeleteTraining($id)
    {
        $user = $this->currentUser();
        if ($user === null) {
            Yii::$app->session->setFlash('danger', 'Silakan login terlebih dahulu.');
            return $this->redirect(['user/login']);
        }
        $model = UserTraining::findOne($id);
        if (!$model->user_id == $user->id) {
            throw new \yii\web\ForbiddenHttpException('You are not authorized to perform this action');
        }
        if (!$model->delete()) {
            Yii::$app->session->setFlash('danger', 'Training failed to delete');
        }
        return $this->redirect(['user/profile', 'tab' => 'training']);
    }

    public function actionLogout()
    {
        $session = Yii::$app->session;
        $session->remove('id');
        $session->remove('role');
        $session->remove('name');
        return $this->redirect(['user/login']);
    }

    public function actionForgotPassword()
    {
        if ($this->currentUser()) {
            return Yii::$app->response->redirect(['user/profile']);
        } else {
            return $this->render('forgot-password');
        }
    }

    public function actionResetPasswordForm($email, $token)
    {
        if (md5($email) !== $token) {
            throw new \yii\web\NotFoundHttpException('Token salah');
        }
        $user = User::find()->where(['email' => $email])->one();
        if ($user) {
            return $this->render('reset-password', [
                'user' => $user,
            ]);
        } else {
            throw new \yii\web\NotFoundHttpException('Email tidak terdaftar');
        }
    }

    public function actionSendResetPasswordEmail()
    {
        $this->enableCsrfValidation = false;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $response = '';
        if (isset($_POST['email'])) {
            $email = $_POST['email'];
            $emailExist = User::find()->where(['email' => $email])->one();
            if (!$emailExist) {
                $response = [
                    'status' => 'error',
                    'message' => 'Email '.$email.' belum pernah terdaftar sebagai user VistaEducation.',
                ];
                return $response;
            }
            if ($_SERVER['SERVER_NAME'] !== 'localhost') {
                /**
                 * Mengirim email reset password
                 */
                $to = $email;
                $subject = 'VistaEducation';
                $textBody = 'Reset Password';
                $params = [
                    'user' => $emailExist,
                    'link' => Url::to(['user/reset-password-form', 'email' => $email, 'token' => md5($email)], true),
                ];
                $sent = Mail::send('noreplyEmail', $to, $subject, $textBody, 'forgot-password-user', $params);
                if ($sent) {
                    Yii::$app->session->setFlash('success', 'Reset password link sent to your email');
                } else {
                    Yii::$app->session->setFlash('danger', 'Password reset link failed to sent to your email');
                }
            }
            $response = [
                'status' => 'success',
                'message' => 'We have been sent password reset link. Check your inbox. '.Url::to(['user/reset-password-form', 'email' => $email, 'token' => md5($email)], true),
            ];
            return $response;

        } else {
            $response = [
                'status' => 'error',
                'message' => 'Email is wrong, make sure fill with your registered email',
            ];
            return $response;
        }
    }

    public function actionResetPassword()
    {
        $this->enableCsrfValidation = false;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $response = '';
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $user = User::find()->where(['email' => $email])->one();
            if (!$user) {
                $response = [
                    'status' => 'error',
                    'message' => 'Email '.$email.' has never been registered in VistaEducation.',
                ];
                return $response;
            }
            $user->password = Yii::$app->security->generatePasswordHash($password);
            if ($user->save()) {
                $response = [
                    'status' => 'success',
                    'message' => 'Your password successfully updated',
                ];
                return $response;
            } else {
                $message = '';
                foreach ($user->errors as $error) {
                    $message = $error[0];
                }
                $response = [
                    'status' => 'error',
                    'message' => $message,
                ];
                return $response;
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Fill your new password',
            ];
            return $response;
        }
    }

    public function actionEmailActivation()
    {
        if ($this->currentUser()) {
            return Yii::$app->response->redirect(['user/profile']);
        } else {
            return $this->render('email-activation');
        }
    }

    public function actionSendActivationEmail()
    {
        $this->enableCsrfValidation = false;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $response = '';
        if (isset($_POST['email'])) {
            $email = $_POST['email'];
            $emailExist = User::find()->where(['email' => $email])->one();
            if (!$emailExist) {
                $response = [
                    'status' => 'error',
                    'message' => 'Email '.$email.' never been registered in VistaEducation.',
                ];
                return $response;
            }
            if ($_SERVER['SERVER_NAME'] !== 'localhost') {
                /**
                 * Mengirim email konfirmasi
                 */
                /*
                $to = $emailExist->email;
                $subject = 'VistaEducation';
                $textBody = 'Konfirmasi Email';
                $body = 'Dear '.$emailExist->nama.', email ini didaftarkan sebagai user di VistaEducation. Jika Anda merasa telah mendaftar, silakan konfirmasi email dengan klik link di bawah ini:<br>';
                $body .= Html::a('klik untuk konfirmasi', Yii::getAlias('@fronturl') . '/user/confirmation?email=' . urlencode($emailExist->email) . '&token=' . md5($emailExist->id));
                $body .= '<br><br><br>Terima kasih,<br>';
                $body .= 'VistaEducation';
                $params = ['body' => $body];
                $sent = Mail::send('noreplyEmail', $to, $subject, $textBody, 'notification-mail', $params);
                if ($sent) {
                    Yii::$app->session->setFlash('success', 'Confirmation link sent to your email');
                } else {
                    Yii::$app->session->setFlash('danger', 'Confirmation link failed to sent to your email');
                }
                */
            }
            $response = [
                'status' => 'success',
                'message' => 'We have sent an activation link to your mail. Check your email.',
            ];
            return $response;
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Make sure you fill with your registered email',
            ];
            return $response;
        }
    }

    public function actionChangePassword()
    {
        $user = $this->currentUser();
        if ($user === null) {
            Yii::$app->session->setFlash('danger', 'Silakan login terlebih dahulu.');
            return $this->redirect(['user/login']);
        }
        $user->mode = 'change-password';
        if ($user->load(Yii::$app->request->post())) {
            $user->password = password_hash($user->new_password, PASSWORD_DEFAULT);
            if ($user->save()) {
                Yii::$app->session->setFlash('success', 'Password has been changed');
            } else {
                foreach ($user->errors as $error) {
                    Yii::$app->session->setFlash('danger', 'Password failed to change. '.$error[0]);
                }
                return $this->redirect(['user/change-password']);
            }
            return $this->redirect(['user/profile']);
        } else {
            return $this->render('change-password', [
                'user' => $user,
            ]);
        }
    }

    /**
     * Mengambil user yang sedang login dari session.
     * Session `id` diisi oleh actionLogin() saat autentikasi berhasil.
     *
     * @return \common\models\User|null
     */
    private function currentUser()
    {
        $id = Yii::$app->session->get('id');
        if (!$id) {
            return null;
        }
        return User::findOne($id);
    }
}
