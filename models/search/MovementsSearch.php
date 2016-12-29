<?php

namespace app\models\search;

use app\models\custom\AuxData;
use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Movements;

/**
 * MovementsSearch represents the model behind the search form about `app\models\Movements`.
 */
class MovementsSearch extends Movements
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'materials_id', 'stocks_id'], 'integer'],
            [['qty'], 'number'],
            [['from_to', 'transaction_date', 'person_in_charge', 'person_receiver', 'docref', 'direction'], 'safe'],
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
        $query = Movements::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $directions = AuxData::getDirections();
        $direction = NULL;
        if (in_array($params['MovementsSearch']['direction'], $directions)) {
            $directions = array_flip($directions);
            $direction = $directions [$params['MovementsSearch']['direction']];
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }




        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'materials_id' => $this->materials_id,
            'direction' => $direction,
            'qty' => $this->qty,
            'transaction_date' => $this->transaction_date,
            'stocks_id' => $this->stocks_id,
        ]);

        $query->andFilterWhere(['like', 'from_to', $this->from_to])
            ->andFilterWhere(['like', 'person_in_charge', $this->person_in_charge])
            ->andFilterWhere(['like', 'person_receiver', $this->person_receiver])
            ->andFilterWhere(['like', 'docref', $this->docref]);

        return $dataProvider;
    }
}
