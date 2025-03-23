<?php
namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Product;

class ProductSearch extends Product
{
    public $category_name;

    public function rules()
    {
        return [
            [['id', 'category_id'], 'integer'],
            [['name', 'slug', 'short_description'], 'safe'],
            [['price'], 'number'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Product::find();  // Используем таблицу `product`

        // Настроим связь для фильтрации по категории, если необходимо
        // $query->joinWith('category'); // Если у вас есть связь с категорией

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
            'price' => $this->price,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
              ->andFilterWhere(['like', 'slug', $this->slug])
              ->andFilterWhere(['like', 'short_description', $this->short_description]);

        return $dataProvider;
    }
}
?>