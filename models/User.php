<?php

namespace mdm\admin\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $user_id
 * @property string $user_name
 * @property string $user_passwd_hash
 * @property string $user_passwd_token
 * @property string $user_email
 * @property string $user_tel
 * @property string $shop_id
 * @property string $user_auth_key
 * @property integer $user_status
 * @property integer $user_created
 * @property integer $user_updated
 * @property string $password write-only password
 * @property string $user_type
 * @property string $user_role_type
 *
 * @property UserProfile $profile
 */
class User extends ActiveRecord implements IdentityInterface {

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'user_created',
                'updatedAtAttribute' => 'user_updated',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_name', 'user_auth_key', 'user_passwd_hash', 'user_email', 'user_tel'], 'required'],
            [['user_tel', 'shop_id', 'user_status', 'user_created', 'user_updated'], 'integer'],
            [['user_name', 'user_passwd_hash', 'user_passwd_token', 'user_email'], 'string', 'max' => 255],
            [['user_auth_key', 'user_role_type','user_type'], 'string', 'max' => 32],
            [['user_name'], 'unique'],
            [['user_email'], 'unique'],
            [['user_tel'], 'unique'],
            [['user_passwd_token'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'user_id' => 'User ID',
            'user_name' => 'User Name',
            'user_auth_key' => 'User Auth Key',
            'user_passwd_hash' => 'User Passwd Hash',
            'user_passwd_token' => 'User Passwd Token',
            'user_email' => 'User Email',
            'shop_id' => 'Shop ID',
            'user_tel' => 'User Tel',
            'user_status' => 'User Status',
            'user_created' => 'User Created',
            'user_updated' => 'User Updated',
            'user_type' => 'User Type',
            'user_role_type' => 'User Role Type',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['user_id' => $id, 'user_status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->user_auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Finds user by user_name
     *
     * @param string $userName
     * @return static|null
     */
    public static function findByUserName($userName) {
        return static::findOne(['user_name' => $userName, 'user_status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByResetToken($token) {
        if (!static::isResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                    'user_passwd_token' => $token,
                    'user_status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isResetTokenValid($token) {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.userPasswdTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * Validates user_passwd_hash
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->user_passwd_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->user_passwd_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->user_auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordToken() {
        $this->user_passwd_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordToken() {
        $this->user_passwd_token = null;
    }

}
