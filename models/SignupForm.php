<?php

namespace app\models;

use yii\base\Model;

class SignupForm extends Model
{
    public $username;
    public $password;
    public $is_admin = false;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['username', 'string', 'min' => 4, 'max' => 255],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'Пользователь уже существует.'],
            ['password', 'string', 'min' => 6],
            ['is_admin', 'boolean'],
        ];
    }

    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->is_admin = $this->is_admin;

        return $user->save() ? $user : null;
    }
}
