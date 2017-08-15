<?php
namespace mdm\admin\models\form;

use mdm\admin\models\User;
use yii\base\Model;

/**
 * Signup form
 */
class Signup extends Model
{
    public $user_name;
    public $user_email;
    public $user_tel;
    public $shop_id;
    public $user_passwd;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['user_name', 'filter', 'filter' => 'trim'],
            ['user_name', 'required'],
            ['user_name', 'unique', 'targetClass' => 'mdm\admin\models\User', 'message' => 'This user name has already been taken.'],
            ['user_name', 'string', 'min' => 2, 'max' => 255],

            ['user_email', 'filter', 'filter' => 'trim'],
            ['user_email', 'required'],
            ['user_email', 'email'],
            ['user_email', 'unique', 'targetClass' => 'mdm\admin\models\User', 'message' => 'This user email address has already been taken.'],
        		
        	['user_tel', 'filter', 'filter' => 'trim'],
        	['user_tel', 'required'],
        	['user_tel', 'unique', 'targetClass' => 'mdm\admin\models\User', 'message' => 'This user tel has already been taken.'],
        	['user_tel', 'string', 'min' => 11],

            ['user_passwd', 'required'],
            ['user_passwd', 'string', 'min' => 6],
        		
        	['shop_id', 'filter', 'filter' => 'trim'],
        	['shop_id', 'required'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->user_name = $this->user_name;
            $user->user_email = $this->user_email;
            $user->user_tel = $this->user_tel;
            $user->shop_id = $this->shop_id;
            $user->setPassword($this->user_passwd);
            $user->generateAuthKey();
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }
}
