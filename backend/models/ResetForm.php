<?php
namespace backend\models;

use yii\base\Model;
use common\models\Adminuser;

/**
 * Signup form
 */
class ResetForm extends Model
{

    public $password;
    public $repeatPwd;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['repeatPwd','compare','compareAttribute'=>'password','message'=>"两次输入密码不正确"],
        ];
    }


    public function attributeLabels()
    {
        return [
            'password' => '密码',
            'repeatPwd' => '确认密码',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function resetpassword($id)
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = Adminuser::findOne($id);
        $user->setPassword($this->password);
        $user->removePasswordResetToken();

        return $user->save() ? true : false;
    }
}
