<?php

namespace app\controllers;

use Yii;
use app\models\Song;
use app\models\SongSearch;
use app\models\Playlist;
use app\models\PlaylistSong;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * SongController implements the CRUD actions for Song model.
 */
class SongController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Song models.
     * @return mixed
     */
    public function actionIndex($playlist_id = 0)
    {
        $searchModel = new SongSearch();
        $searchModel->playlist_id = $playlist_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        

        if($playlist_id > 0)
        {
			//$dataProvider->pagination->pageSize = 120;
			$dataProvider->pagination = false;
        	return $this->render('index_playlist', [
        			'searchModel' => $searchModel,
        			'dataProvider' => $dataProvider,
        			'playlist_id' => $playlist_id,
        	]);
        }
        else
        {
			$dataProvider->pagination->pageSize = 30;
        	$items = ArrayHelper::map(Playlist::find()->all(), 'playlist_id', 'playlist_name');
        	
        	return $this->render('index', [
        			'searchModel' => $searchModel,
        			'dataProvider' => $dataProvider,
        			'items' => $items,
        	]);
        }
        
    }
    
    public function actionSortPlaylist($playlist_id, $column = "filename", $type = SORT_ASC) {

        echo "Sort Playlist...<br>";

        if($type == "SORT_DESC") {
                $type = SORT_DESC;
        }
        else {
                $type = SORT_ASC;
        }

        $songs = Song::find()->joinWith('playlistSongs PLAY')->where([ 'PLAY.playlist_id' => $playlist_id])->orderBy([ $column => $type])->all();

        foreach($songs as $key => $song)  {
                $temp = PlaylistSong::find()->where([ 'playlist_id' => $playlist_id ])->andWhere([ 'song_id' => $song->song_id ])->one();
                echo $song->song_id . " | " . $song->filename . " | Position: " . $temp->position;
                echo " => " . ( $key+1 ) . "<br>";

                if($temp->position != ( $key+1 )) {
                        echo "==> Update position...";
                        $temp->position = ( $key+1 );
                        if($temp->save()) {
                                echo " done!<br>";
                        }
                        else {
                                die("Failure: Couldnt save position!");
                        }
                }
        }

    }
    
    public function actionRandom()
    {
    	$searchModel = new SongSearch();
    	$searchModel->playlist_id = -1;
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    	$dataProvider->pagination->pageSize = 50;
    
    	return $this->render('index_random', [
    		'searchModel' => $searchModel,
    		'dataProvider' => $dataProvider,
    	]);
    }
    
    public function actionSongPlayed($song_id = 0)
    {
    	if($song_id != 0)
    	{
    		$query = Song::find()
    			->where('song_id = :song_id')
    			->addParams([':song_id' => $song_id])
    			->one();
    		
    		$query->setSongPlayed();
    		echo "Increased Song_ID!";
    	}
    	else
    	{
    		echo "Invalid Song_ID!";
    	}
    }
    
    public function actionAddToPlaylist()
    {
    	if(Yii::$app->request->isAjax)
    	{
    		$data = Yii::$app->request->post();
    		$status = 0;
    		
    		$position = PlaylistSong::find()
    		->where('playlist_id = :playlist_id')
    		->addParams([':playlist_id' => $data['playlist_id']])
    		->max('position');

    		foreach($data['song_id'] AS $song_id)
    		{ 
    			$song = new PlaylistSong();
    			$song->song_id = $song_id;
    			$song->playlist_id = $data['playlist_id'];
    			if($position == 0)
    			{
    				$position = 0;
    				$song->position = $position++;
    			}
    			else
    			{
    				$song->position = ++$position;
    			}
    			
    			if($song->save())
    			{
    				$status = 1;
    			}
    		}
    		
    		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    		return [
    			'status' => $status,
    		];
    		
    	}
    	else
    	{
    		return "No Ajax!";
    	}
    }

    public function actionRemove($song_id, $playlist_id)
    {
    	PlaylistSong::deleteAll('song_id = :song_id AND playlist_id = :playlist_id', ['song_id' => $song_id, 'playlist_id' => $playlist_id]);
    	
    	$songs = PlaylistSong::find()->where('playlist_id = :playlist_id')
    			->addParams(['playlist_id' => $playlist_id])
    			->orderBy(['position' => SORT_ASC])
    			->all();
    	
    	$position = 0;
    	foreach($songs AS $song)
    	{
    		$song->position = $position++;
    		$song->save();
    	}
    	
    	return $this->redirect(['song/index', 'playlist_id' => $playlist_id]);
    }
    
    public function actionRateUp()
    {
    	$data = Yii::$app->request->post();
    	
    	$query = Song::findOne(['song_id' => $data['song_id']]);
    	
    	if($query->setRateUp())
    	{
    		$status = 'ok';
    	}
    	else
    	{
    		$status = 'error';
    	}
    	
    	\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	return [
    			'song_id' => $data['song_id'],
    			'status' => $status,
    	];
    }
    
    public function actionRateDown()
    {
    	$data = Yii::$app->request->post();
    	 
    	$query = Song::findOne(['song_id' => $data['song_id']]);
    	 
    	if($query->setRateDown())
    	{
    		$status = 'ok';
    	}
    	else
    	{
    		$status = 'error';
    	}
    	 
    	\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	return [
    			'song_id' => $data['song_id'],
    			'status' => $status,
    	];
    }
    
    /**
     * Displays a single Song model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Song model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Song();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->song_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Song model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->song_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Song model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Song model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Song the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Song::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
