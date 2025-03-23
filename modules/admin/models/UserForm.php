<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use app\models\User;

/**
 * UserForm - модель формы для управления пользователями в админке
 */
class UserForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password_confirm;
    public $role;
    public $status;
    public $first_name;
    public $last_name;
    public $phone;
    public $avatarFile;
    
    // Сценарии
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // Обязательные поля
            [['username', 'email'], 'required'],
            [['password', 'password_confirm'], 'required', 'on' => self::SCENARIO_CREATE],
            
            // Валидация username
            ['username', 'string', 'min' => 4, 'max' => 255],
            [
                'username',
                'match',
                'pattern' => '/^[a-zA-Z0-9_-]+$/',
                'message' => 'Логин может содержать только буквы, цифры, дефис и подчеркивание'
            ],
            [
                'username',
                'unique',
                'targetClass' => User::class,
                'message' => 'Этот логин уже занят',
                'filter' => function ($query) {
                    if (!$this->isNewRecord()) {
                        $query->andWhere(['not', ['id' => Yii::$app->request->get('id')]]);
                    }
                }
            ],
            
            // Валидация email
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            [
                'email',
                'unique',
                'targetClass' => User::class,
                'message' => 'Этот email уже используется',
                'filter' => function ($query) {
                    if (!$this->isNewRecord()) {
                        $query->andWhere(['not', ['id' => Yii::$app->request->get('id')]]);
                    }
                }
            ],
            
            // Валидация пароля
            ['password', 'string', 'min' => 6],
            [
                'password',
                'match',
                'pattern' => '/^.*(?=.*\d)(?=.*[a-zA-Z]).*$/',
                'message' => 'Пароль должен содержать как минимум одну букву и одну цифру',
                'skipOnEmpty' => true
            ],
            ['password_confirm', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],
            
            // Роль и статус
            ['role', 'in', 'range' => array_keys(User::getRoleList())],
            ['status', 'in', 'range' => array_keys(User::getStatusList())],
            
            // Дополнительные поля
            [['first_name', 'last_name'], 'string', 'max' => 255],
            ['phone', 'string', 'max' => 20],
            ['phone', 'match', 'pattern' => '/^[0-9+\- ()]+$/', 'message' => 'Некорректный формат телефона'],
            
            // Аватар
            ['avatarFile', 'file', 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 1024 * 1024 * 2, 'tooBig' => 'Размер файла не должен превышать 2 МБ'],
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
            'role' => 'Роль',
            'status' => 'Статус',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'phone' => 'Телефон',
            'avatarFile' => 'Аватар',
        ];
    }

    /**
     * Сценарии для модели
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['username', 'email', 'password', 'password_confirm', 'role', 'status', 'first_name', 'last_name', 'phone', 'avatarFile'];
        $scenarios[self::SCENARIO_UPDATE] = ['username', 'email', 'password', 'password_confirm', 'role', 'status', 'first_name', 'last_name', 'phone', 'avatarFile'];
        return $scenarios;
    }

    /**
     * Проверяет, является ли запись новой
     * 
     * @return bool
     */
    protected function isNewRecord()
    {
        return $this->scenario === self::SCENARIO_CREATE;
    }

    /**
     * Сохраняет нового пользователя
     * 
     * @return User|null сохраненная модель пользователя или null в случае ошибки
     */
    public function save()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->role = $this->role ?? User::ROLE_USER;
        $user->status = $this->status ?? User::STATUS_ACTIVE;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->phone = $this->phone;
        
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        // Обработка аватара
        if ($this->uploadAvatar($user)) {
            if ($user->save()) {
                return $user;
            }
        }
        
        return null;
    }

    /**
     * Обновляет существующего пользователя
     * 
     * @param User $user модель пользователя для обновления
     * @return bool успешно ли обновление
     */
    public function update($user)
    {
        if (!$this->validate()) {
            return false;
        }

        $user->username = $this->username;
        $user->email = $this->email;
        $user->role = $this->role;
        $user->status = $this->status;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->phone = $this->phone;
        
        // Обновляем пароль только если он был введен
        if (!empty($this->password)) {
            $user->setPassword($this->password);
        }
        
        // Обработка аватара
        $this->uploadAvatar($user);
        
        return $user->save();
    }

    /**
     * Загружает аватар для пользователя
     * 
     * @param User $user модель пользователя
     * @return bool успешно ли загрузка
     */
    protected function uploadAvatar($user)
    {
        if ($this->avatarFile instanceof UploadedFile) {
            $avatarName = 'avatar_' . time() . '_' . uniqid() . '.' . $this->avatarFile->extension;
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
            } else {
                return false;
            }
        }
        
        return true;
    }
}