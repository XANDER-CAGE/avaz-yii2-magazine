<?php

namespace app\modules\admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch - модель поиска для модели User.
 */
class UserSearch extends Model
{
    public $id;
    public $username;
    public $email;
    public $role;
    public $status;
    public $first_name;
    public $last_name;
    public $created_at;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['username', 'email', 'role', 'first_name', 'last_name', 'created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // обходим scenarios() реализации в родительском классе
        return Model::scenarios();
    }

    /**
     * Создает провайдер данных с примененным поисковым запросом
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // раскомментируйте следующую строку, если не хотите возвращать любые записи при ошибке валидации
            // $query->where('0=1');
            return $dataProvider;
        }

        // Фильтрация по числовым полям
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'role' => $this->role,
        ]);

        // Фильтрация по строковым полям
        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name]);
            
        // Фильтрация по дате
        if ($this->created_at) {
            $dates = explode(' - ', $this->created_at);
            if (count($dates) == 2) {
                $query->andFilterWhere(['between', 'created_at', $dates[0] . ' 00:00:00', $dates[1] . ' 23:59:59']);
            } else {
                $query->andFilterWhere(['like', 'created_at', $this->created_at]);
            }
        }

        return $dataProvider;
    }
}