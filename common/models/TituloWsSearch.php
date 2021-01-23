<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TitulosWs;

/**
 * TituloWsSearch represents the model behind the search form of `common\models\TitulosWs`.
 */
class TituloWsSearch extends TitulosWs
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'ambiente'], 'integer'],
            [['cveInstitucion', 'nombre_archivo', 'numero_lote', 'mensaje', 'fecha_envio'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
    public function searchQa($params)
    {
        $query = TitulosWs::find()->where(['ambiente' => 0]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'fecha_envio' => $this->fecha_envio,
            'ambiente' => $this->ambiente,
        ]);

        $query->andFilterWhere(['like', 'cveInstitucion', $this->cveInstitucion])
            ->andFilterWhere(['like', 'nombre_archivo', $this->nombre_archivo])
            ->andFilterWhere(['like', 'numero_lote', $this->numero_lote])
            ->andFilterWhere(['like', 'mensaje', $this->mensaje]);

        return $dataProvider;
    }

     public function searchProduccion($params)
    {
        $query = TitulosWs::find()->where(['ambiente' => 1]);

        // add conditions that should always apply here

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
            'id' => $this->id,
            'fecha_envio' => $this->fecha_envio,
            'ambiente' => $this->ambiente,
        ]);

        $query->andFilterWhere(['like', 'cveInstitucion', $this->cveInstitucion])
            ->andFilterWhere(['like', 'nombre_archivo', $this->nombre_archivo])
            ->andFilterWhere(['like', 'numero_lote', $this->numero_lote])
            ->andFilterWhere(['like', 'mensaje', $this->mensaje]);

        return $dataProvider;
    }
}
