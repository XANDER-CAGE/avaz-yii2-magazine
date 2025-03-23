<?php

namespace app\models;

use yii\base\InvalidArgumentException;
use yii\base\Model;
use app\models\User;

/**
 * Модель формы сброса пароля
 */
class ResetPasswordForm extends Model
{
    public $password;
    public $password_confirm;

    /**
     * @var \app\models\User
     */
    private $_user;


    /**
     * Создает модель формы, с токеном сброса пароля
     *
     * @param string $token
     * @param array $config имя конфигурации для конструктора Model
     * @throws InvalidArgumentException если токен пустой или невалидный
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException('Токен сброса пароля не может быть пустым.');
        }
        
        $this->_user = User::findByPasswordResetToken($token);
        
        if (!$this->_user) {
            throw new InvalidArgumentException('Неверный токен сброса пароля.');
        }
        
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password', 'password_confirm'], 'required'],
            ['password', 'string', 'min' => 6],
            [
                'password',
                'match',
                'pattern' => '/^.*(?=.*\d)(?=.*[a-zA-Z]).*$/',
                'message' => 'Пароль должен содержать как минимум одну букву и одну цифру'
            ],
            ['password_confirm', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'password' => 'Новый пароль',
            'password_confirm' => 'Подтверждение пароля',
        ];
    }

    /**
     * Сбрасывает пароль.
     *
     * @return bool если пароль был успешно сброшен
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();

        return $user->save(false);
    }
}