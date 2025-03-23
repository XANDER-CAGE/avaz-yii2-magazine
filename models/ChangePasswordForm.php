<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Модель формы изменения пароля
 */
class ChangePasswordForm extends Model
{
    public $current_password;
    public $new_password;
    public $new_password_confirm;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['current_password', 'new_password', 'new_password_confirm'], 'required'],
            ['current_password', 'validateCurrentPassword'],
            ['new_password', 'string', 'min' => 6],
            [
                'new_password',
                'match',
                'pattern' => '/^.*(?=.*\d)(?=.*[a-zA-Z]).*$/',
                'message' => 'Пароль должен содержать как минимум одну букву и одну цифру'
            ],
            ['new_password_confirm', 'compare', 'compareAttribute' => 'new_password', 'message' => 'Пароли не совпадают'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'current_password' => 'Текущий пароль',
            'new_password' => 'Новый пароль',
            'new_password_confirm' => 'Подтверждение нового пароля',
        ];
    }

    /**
     * Валидация текущего пароля
     *
     * @param string $attribute атрибут
     * @param array $params дополнительные параметры
     */
    public function validateCurrentPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = Yii::$app->user->identity;
            
            if (!$user->validatePassword($this->current_password)) {
                $this->addError($attribute, 'Неверный текущий пароль.');
            }
        }
    }

    /**
     * Изменяет пароль пользователя
     *
     * @return bool успешно ли изменение
     */
    public function changePassword()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = Yii::$app->user->identity;
        $user->setPassword($this->new_password);
        
        return $user->save(false);
    }
}