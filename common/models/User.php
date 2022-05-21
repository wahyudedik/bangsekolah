<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\Html;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 'NONACTIVE';
    const STATUS_ACTIVE = 'ACTIVE';

    public $mode;
    public $password;
    public $confirm_password;
    public $mode_create;
    public $mode_add_user;
    public $mode_change_password;
    public $logo;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            // TimestampBehavior::className(),
            'timestamp' => [
                 'class' => 'yii\behaviors\TimestampBehavior',
                 'attributes' => [
                     \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                     \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                 ],
                 'value' => new \yii\db\Expression('NOW()'),
             ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'image', 'username', 'password', 'logo'], 'string', 'max' => 255],

            /**
             * Role pada saat buat user baru, yang required hanya:
             * nik, nama, email, password
             */
            [['name', 'role_id', 'username', 'password'], 'required', 'when' => function($model) {
                return $model->mode_create == true;
            }, 'message' => '{attribute} harus diisi'],
            
            /**
             * Role untuk change password
             * berlaku pada saat mengubah password saja
             */
            [['password', 'confirm_password'], 'required', 'when' => function($model) {
                return $model->mode_change_password == true;
            }, 'message' => '{attribute} harus diisi'],
            [['password', 'confirm_password'], 'string', 'min' => 5, 'when' => function($model) {
                return $model->mode_change_password == true;
            }, 'message' => '{attribute} minimal 5 karakter'],
            [['confirm_password'], 'compare', 'compareAttribute'=>'password', 'skipOnEmpty' => false, 'when' => function($model) {
                return $model->mode_change_password == true;
            }, 'message'=>"Passwords don't match"],

            [['role_id'], 'integer'],
            [['email'], 'email', 'message' => 'Email tidak valid'],
            [['register', 'mode_create', 'mode_add_user'], 'safe'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Full Name',
            'role_id' => 'Role',

            'confirmationStatus' => 'Confirm Status',
            'profileStatus' => 'Profile',
            'experienceStatus' => 'Experience',
            'trainingStatus' => 'Training',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
        foreach (self::$users as $user) {
            if ($user['id'] === (string) $token->getClaim('uid')) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function getRole()
    {
        return $this->hasOne(Role::classname(), ['id' => 'role_id']);
    }

    public function hasMenu($id)
    {
        foreach ($this->role->roleMenus as $roleMenu) {
            if ($roleMenu->menu_id == $id) return true;
        }
        return false;
    }

    public function getRoleLink()
    {
        return Html::a($this->role->nama, ['role/view', 'id' => $this->role->id]);
    }

    public static function can($checkController = null, $checkAction = null)
    {
        $user = Yii::$app->user->identity;
        if ($checkController == null) $checkController = Yii::$app->controller->id;
        if ($checkAction == null) $checkAction = Yii::$app->controller->action->id;
        $response = false;
        foreach ($user->role->roleMenus as $roleMenu) {
            if ($roleMenu->menu->controller === $checkController) {
                foreach (json_decode($roleMenu->privileges) as $action) {
                    if ($action === $checkAction) {
                        $response = true;
                        break;
                    }
                }
            }
        }
        return $response;
    }

    public function getGenderName()
    {
        return $this->gender == 1 ? 'Pria' : 'Wanita';
    }

    public function getSmallPhoto()
    {
        $baseUrl = Yii::$app->request->baseUrl;
        if ($this->foto_small == '') {
            $src = 'default-user.png';
        } else {
            $src = $this->foto_small;
        }
        return Html::img($baseUrl.'/uploads/user/'.$src, ['style' => 'width: 20px; border-radius: 20px;']);
    }

    public function getUserCareers()
    {
        return $this->hasMany(UserCareer::className(), ['user_id' => 'id']);
    }

    public function getUserProfiles()
    {
        return $this->hasMany(UserProfile::className(), ['user_id' => 'id']);
    }

    public function getUserExperiences()
    {
        return $this->hasMany(UserExperience::className(), ['user_id' => 'id']);
    }

    public function getUserTrainings()
    {
        return $this->hasMany(UserTraining::className(), ['user_id' => 'id']);
    }

    public function getProfileStatus()
    {
        if (count($this->userProfiles) == 0) {
            return '<label class="label label-default">Null</i>';
        } else {
            return '<label class="label label-success">Exists</i>';
        }
    }

    public function getExperienceStatus()
    {
        if (count($this->userExperiences) == 0) {
            return '<label class="label label-default">Null</i>';
        } else {
            return '<label class="label label-success">Exists</i>';
        }
    }

    public function getTrainingStatus()
    {
        if (count($this->userTrainings) == 0) {
            return '<label class="label label-default">Null</i>';
        } else {
            return '<label class="label label-success">Exists</i>';
        }
    }

    public function getConfirmationStatus()
    {
        if ($this->status == 0) {
            return '<label class="label label-default">Not Confirmed</i>';
        } else {
            return '<label class="label label-success">Email Confirmed</i>';
        }
    }

}
