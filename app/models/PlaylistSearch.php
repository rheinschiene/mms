<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Playlist;

/**
 * PlaylistSearch represents the model behind the search form about `app\models\Playlist`.
 */
class PlaylistSearch extends Playlist
{
	public $groupName;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['playlist_id', 'group_id'], 'integer'],
            [['playlist_name'], 'safe'],
        	[['groupName'], 'safe'],
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
        $query = Playlist::find();
        $query->joinWith('songs');
        $query->joinWith('group');
        //$query->average('last_played');
        $query->groupBy('playlist_id');
        //$query->orderBy(['AVG(last_played)' => SORT_ASC]);
        //$query->average('last_played AS test');
        //$query->groupBy('test');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $dataProvider->setSort([
        		'attributes' => [
        				'playlist_id',
        				'group_id',
        				'groupName' => [
        					'asc' => ['playlist_group.group_name' => SORT_ASC],
        					'desc' => ['playlist_group.group_name' => SORT_DESC],
        				],
        				'playlist_name',
        				'songLastPlayed' => [
        					'asc' => ['AVG(last_played)' => SORT_ASC],
        					'desc' => ['AVG(last_played)' => SORT_DESC],
        				],
        				/*'artistName' => [
        						'asc' => ['artist.artist_name' => SORT_ASC],
        						'desc' => ['artist.artist_name' => SORT_DESC],
        				],*/
        
        		]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'playlist.playlist_id' => $this->playlist_id,
            'playlist.group_id' => $this->group_id,
        ]);

        $query->andFilterWhere(['like', 'playlist_name', $this->playlist_name])
        	->andFilterWhere(['like', 'group_name', $this->groupName]);

        return $dataProvider;
    }
}
