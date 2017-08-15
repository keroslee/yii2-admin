<?php

namespace mdm\admin\models\searchs;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use mdm\admin\models\User as UserModel;

/**
 * User represents the model behind the search form about `mdm\admin\models\User`.
 */
class User extends UserModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'user_tel', 'user_status', 'user_created', 'user_updated'], 'integer'],
            [['user_name', 'user_auth_key', 'user_passwd_hash', 'user_passwd_token', 'user_email'], 'safe'],
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
        $query = UserModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('1=0');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'user_id' => $this->user_id,
        	'user_tel' => $this->user_tel,
            'user_status' => $this->user_status,
            'user_created' => $this->user_created,
            'user_updated' => $this->user_updated,
        ]);

        $query->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'user_auth_key', $this->user_auth_key])
            ->andFilterWhere(['like', 'user_passwd_hash', $this->user_passwd_hash])
            ->andFilterWhere(['like', 'user_passwd_token', $this->user_passwd_token])
            ->andFilterWhere(['like', 'user_email', $this->user_email]);

        return $dataProvider;
    }
}
