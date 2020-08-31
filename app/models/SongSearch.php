<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Song;

/**
 * SongSearch represents the model behind the search form about `app\models\Song`.
 */
class SongSearch extends Song
{
	public $artistName;
	public $albums;
	public $playlist_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['song_id', 'artist_id', 'year', 'count_played', 'valid'], 'integer'],
            [['title', 'filename', 'path', 'genre', 'last_played', 'checksum', 'length', 'bitrate'], 'safe'],
            [['rating'], 'number'],
        	[['artistName'], 'safe'],
        	[['albums'], 'safe'],
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
        $query = Song::find();
        $query->joinWith('artist');
        $query->joinWith('albums');
        if($this->playlist_id > 0)
        {
	        $query->joinWith('playlists');
	        $query->where('playlist.playlist_id = :playlist_id');
	        $query->addParams([':playlist_id' => $this->playlist_id]);
        }
        else if($this->playlist_id < 0)
        {
        	$query->where('path NOT LIKE "%Filmmusik%" AND path NOT LIKE "%Ayla%"');
        	$query->orderBy(['last_played' => SORT_ASC]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        if($this->playlist_id > 0)
        {
	        $dataProvider->setSort([
	        		'defaultOrder' => [
	        				'playlistPosition' => SORT_ASC,
	        		],
	        		'attributes' => [
	        				'title',
	        				'song_id',
	        				'artist_id',
	        				'playlistPosition' => [
	        						'asc' => ['playlist_song.position' => SORT_ASC],
	        						'desc' => ['playlist_song.position' => SORT_DESC],
	        				],
	        				'artistName' => [
	        						'asc' => ['artist.artist_name' => SORT_ASC],
	        						'desc' => ['artist.artist_name' => SORT_DESC],
	        				],
	        				'albums' => [
	        						'asc' => ['album.album_name' => SORT_ASC],
	        						'desc' => ['album.album_name' => SORT_DESC],
	        				],
	        				'last_played',
	        		]
	        ]);
        }
        else if($this->playlist_id < 0)
        {
        	$dataProvider->sort = false;
        }
        else
        {
        	$dataProvider->setSort([
        		'defaultOrder' => [
        			//'playlistPosition' => SORT_ASC,
        		],
        		'attributes' => [
        			'title',
        			'song_id',
        			'artist_id',
        			'artistName' => [
        				'asc' => ['artist.artist_name' => SORT_ASC],
        				'desc' => ['artist.artist_name' => SORT_DESC],
        			],
        			'albums' => [
        				'asc' => ['album.album_name' => SORT_ASC],
        				'desc' => ['album.album_name' => SORT_DESC],
        			],
        			'last_played',
        			'rating',
        		]
        	]);
        	
        }

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'song.song_id' => $this->song_id,
            'song.artist_id' => $this->artist_id,
            'rating' => $this->rating,
            'year' => $this->year,
            'last_played' => $this->last_played,
            'count_played' => $this->count_played,
            'valid' => $this->valid,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'filename', $this->filename])
            ->andFilterWhere(['like', 'path', $this->path])
            ->andFilterWhere(['like', 'genre', $this->genre])
            ->andFilterWhere(['like', 'checksum', $this->checksum])
            ->andFilterWhere(['like', 'length', $this->length])
            ->andFilterWhere(['like', 'bitrate', $this->bitrate])
        	->andFilterWhere(['like', 'artist_name', $this->artistName])
        	->andFilterWhere(['like', 'album_name', $this->albums]);

        return $dataProvider;
    }
}