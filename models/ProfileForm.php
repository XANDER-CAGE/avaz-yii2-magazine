<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Модель формы редактирования профиля
 */
class ProfileForm extends Model
{
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $avatarFile;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'email'], 'required'],
            [['first_name', 'last_name', 'email'], 'string', 'max' => 255],
            ['email', 'email'],
            [
                'email',
                'unique',
                'targetClass' => User::class,
                'message' => 'Этот email уже используется.',
                'filter' => function ($query) {
                    $query->andWhere(['not', ['id' => Yii::$app->user->id]]);
                }
            ],
            ['phone', 'string', 'max' => 20],
            ['phone', 'match', 'pattern' => '/^[0-9+\- ()]+$/', 'message' => 'Некорректный формат телефона'],
            ['avatarFile', 'file', 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 1024 * 1024 * 2, 'tooBig' => 'Размер файла не должен превышать 2 МБ'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'email' => 'Email',
            'phone' => 'Телефон',
            'avatarFile' => 'Аватар',
        ];
    }

    /**
     * Обновляет профиль пользователя
     * 
     * @param User $user модель пользователя
     * @return bool успешно ли обновление
     */
    public function update($user)
    {
        if (!$this->validate()) {
            return false;
        }

        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        
        // Если email изменился, нужно его подтвердить снова
        if ($user->email !== $this->email) {
            $user->email = $this->email;
            
            if (Yii::$app->params['enableEmailVerification'] ?? false) {
                $user->status = User::STATUS_INACTIVE;
                $user->generateEmailVerificationToken();
                
                // Отправляем письмо для подтверждения нового email
                $this->sendEmailVerification($user);
            }
        }
        
        $user->phone = $this->phone;
        
        // Обработка аватара
        if ($this->avatarFile) {
            $avatarName = 'avatar_' . $user->id . '_' . time() . '.' . $this->avatarFile->extension;
            $avatarPath = Yii::getAlias('@webroot/uploads/avatars/');
            
            // Создаем директорию, если она не существует
            if (!file_exists($avatarPath)) {
                mkdir($avatarPath, 0777, true);
            }
            
            // Сохраняем файл
            if ($this->avatarFile->saveAs($avatarPath . $avatarName)) {
                // Если был старый аватар, удаляем его
                if ($user->avatar && file_exists(Yii::getAlias('@webroot') . $user->avatar)) {
                    unlink(Yii::getAlias('@webroot') . $user->avatar);
                }
                
                $user->avatar = '/uploads/avatars/' . $avatarName;
            }
        }
        
        return $user->save();
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
            ->setSubject('Подтверждение адреса электронной почты на сайте ' . Yii::$app->name)
            ->send();
    }
}