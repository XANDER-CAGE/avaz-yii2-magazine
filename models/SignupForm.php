<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

/**
 * Модель формы регистрации
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password_confirm;
    public $first_name;
    public $last_name;
    public $phone;
    public $terms_accepted;
    public $is_admin = false; // только для администраторов

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // Обязательные поля
            [['username', 'email', 'password', 'password_confirm'], 'required'],
            ['terms_accepted', 'required', 'requiredValue' => 1, 'message' => 'Вы должны принять условия использования'],
            
            // Проверка username
            ['username', 'trim'],
            ['username', 'string', 'min' => 4, 'max' => 255],
            [
                'username',
                'match',
                'pattern' => '/^[a-zA-Z0-9_-]+$/',
                'message' => 'Логин может содержать только буквы, цифры, дефис и подчеркивание'
            ],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'Этот логин уже занят'],
            
            // Проверка email
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'Этот email уже используется'],
            
            // Проверка пароля
            ['password', 'string', 'min' => 6],
            [
                'password',
                'match',
                'pattern' => '/^.*(?=.*\d)(?=.*[a-zA-Z]).*$/',
                'message' => 'Пароль должен содержать как минимум одну букву и одну цифру'
            ],
            ['password_confirm', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],
            
            // Дополнительные поля
            [['first_name', 'last_name'], 'string', 'max' => 255],
            ['phone', 'string', 'max' => 20],
            ['phone', 'match', 'pattern' => '/^[0-9+\- ()]+$/', 'message' => 'Некорректный формат телефона'],
            
            // Флажки
            ['terms_accepted', 'boolean'],
            ['is_admin', 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'email' => 'Email',
            'password' => 'Пароль',
            'password_confirm' => 'Подтверждение пароля',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'phone' => 'Телефон',
            'terms_accepted' => 'Я принимаю условия использования сайта',
            'is_admin' => 'Зарегистрировать как администратора',
        ];
    }

    /**
     * Регистрация пользователя
     *
     * @return User|null Модель пользователя или null в случае ошибки
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->phone = $this->phone;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        
        // Определение роли и статуса
        if ($this->is_admin) {
            $user->role = User::ROLE_ADMIN;
            $user->status = User::STATUS_ACTIVE; // Администраторы активны сразу
        } else {
            $user->role = User::ROLE_USER;
            
            // Если используем подтверждение email, то статус неактивный
            if (Yii::$app->params['enableEmailVerification'] ?? false) {
                $user->status = User::STATUS_INACTIVE;
            } else {
                $user->status = User::STATUS_ACTIVE;
            }
        }

        if ($user->save()) {
            // Отправка письма с подтверждением email
            if ($user->status === User::STATUS_INACTIVE) {
                $this->sendEmailVerification($user);
            }
            
            return $user;
        }
        
        return null;
    }

    /**
     * Отправка письма для подтверждения email
     *
     * @param User $user Модель пользователя
     * @return bool Результат отправки
     */
    protected function sendEmailVerification($user)
    {
        return Yii::$app->mailer->compose(
            ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
            ['user' => $user]
        )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' (робот)'])
            ->setTo($user->email)
            ->setSubject('Подтверждение регистрации на сайте ' . Yii::$app->name)
            ->send();
    }
}