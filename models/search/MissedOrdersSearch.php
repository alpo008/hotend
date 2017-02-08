<?php

namespace app\models\search;


use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Orders;


/**
 * OrdersSearch represents the model behind the search form about `app\models\Orders`.
 */
class MissedOrdersSearch extends Orders
{
    /**
     * @return array
     */
    public function attributes()
    {
        return array_merge(parent::attributes(), ['materials.name', 'materials.ref', 'materials.qty']);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['materials_id'], 'integer'],
            [['qty', 'materials.qty'], 'number'],
            [['id', 'order_date', 'status', 'docref', 'materials.name', 'materials.ref'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Orders::find()
            ->where(['<', 'status', '2'])
            ->orderBy('order_date DESC')
            ->joinWith('materials')
            ->andFilterWhere(['>=', '([[materials.minqty]] - [[materials.qty]])', 0]);
        

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            //'id' => $this->id,
            'materials_id' => $this->materials_id,
            'orders.qty' => $this->qty,
            'order_date' => $this->order_date,
        ]);

        $query->joinWith('materials');


        $query->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'person', $this->person])
            ->andFilterWhere(['like', 'docref', $this->docref])
        ->andFilterWhere(['LIKE', 'materials.name', $this->getAttribute('materials.name')])
        ->andFilterWhere(['LIKE', 'materials.ref', $this->getAttribute('materials.ref')]);


        return $dataProvider;
    }

}
