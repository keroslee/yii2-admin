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
 * @property integer $id
 * @property string $name
 * @property string $password_hash
 * @property string $password_token
 * @property string $email
 * @property string $tel
 * @property string $shop_id
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $type
 * @property string $role_type
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
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'auth_key', 'password_hash', 'email', 'tel'], 'required'],
            [['tel', 'shop_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'password_hash', 'password_token', 'email'], 'string', 'max' => 255],
            [['auth_key', 'role_type','type'], 'string', 'max' => 32],
            [['name'], 'unique'],
            [['email'], 'unique'],
            [['tel'], 'unique'],
            [['password_token'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'User ID',
            'name' => 'User Name',
            'auth_key' => 'User Auth Key',
            'password_hash' => 'User Passwd Hash',
            'password_token' => 'User Passwd Token',
            'email' => 'User Email',
            'shop_id' => 'Shop ID',
            'tel' => 'User Tel',
            'status' => 'User Status',
            'created_at' => 'User Created',
            'updated_at' => 'User Updated',
            'type' => 'User Type',
            'role_type' => 'User Role Type',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
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
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Finds user by name
     *
     * @param string $userName
     * @return static|null
     */
    public static function findByUserName($userName) {
        return static::findOne(['name' => $userName, 'status' => self::STATUS_ACTIVE]);
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
                    'password_token' => $token,
                    'status' => self::STATUS_ACTIVE,
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
     * Validates password_hash
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordToken() {
        $this->password_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordToken() {
        $this->password_token = null;
    }

}
