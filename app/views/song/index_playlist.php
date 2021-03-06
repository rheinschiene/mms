<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Song;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SongSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Songs';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('@web/js/player.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div class="song-index">

    <h1><?= Html::encode($this->title.'.playlist('.$playlist_id.')') ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	<?php 
		$request = Yii::$app->request;
		if($request->getQueryParam('randomize') == "true")
		{
			echo "Random Playlist";
			
			$data_array = $dataProvider->getModels();
			$key_array = $dataProvider->getKeys();
			shuffle($data_array);
			
			$i = 0;
			foreach($data_array AS $data)
			{
				$key_array[$i++] = $data->song_id;
			}
			
			$dataProvider->setModels($data_array);
			$dataProvider->setKeys($key_array);
		
		}
	?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'song_id',
        	[
    			'attribute' => 'playlistPosition',
        		'label' => 'Position',
    			'value' => function($model) use ($playlist_id)
    			{
        			$model->playlist_id = $playlist_id;
    				return $model->playlistPosition;
    			}
   			],
            //'artist_id',
            'title',
            //'filename',
            //'path',
        	'artistName',
        	[
    			'attribute' => 'albums',
    			'value' => function($model)
    			{
    				return implode(', ', ArrayHelper::map($model->albums, 'album_id', 'album_name'));
    			}
   			],
            'rating',
            // 'year',
            // 'genre',
            [
            		'attribute' => 'last_played',
            		//'contentOptions' => function($model, $key, $index, $grid)
        			//{ 
            			//return ['data-lastplayed_song_id' => $model->song_id];
   					//}
   			],
            // 'count_played',
            // 'checksum',
            // 'length',
            // 'bitrate',
            // 'valid',

            ['class' => 'yii\grid\ActionColumn',
            		'template' => '{play}&nbsp;{view}&nbsp;{update}&nbsp;{remove}&nbsp;{delete}&nbsp;{rate_up}&nbsp;{rate_down}',
            		'buttons' => [
            			'play' => function($url, $model){
            				return Html::a('<span class="glyphicon glyphicon-play"></span>', '', ['title' => 'Play']);
            			},
            			'remove' => function($url, $model) {
            				return Html::a('<span class="glyphicon glyphicon-remove index-playlist_remove"></span>', 'index.php?r=song%2Fremove&song_id='.$model->song_id.'&playlist_id='.$model->playlist_id, ['title' => 'Remove from Playlist']);
            			},
            			'rate_up' => function($url, $model) {
            				return Html::a('<span class="glyphicon glyphicon-star"></span>', 'index.php', ['title' => 'Rate up']);
            			},
            			'rate_down' => function($url, $model) {
            				return Html::a('<span class="glyphicon glyphicon-star-empty"></span>', 'index.php', ['title' => 'Rate down']);
            			},
            			/*'view' => function($url, $model){
            				return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 'index.php?r=song%2Fview&id='.$model->song_id, ['title' => 'View']);
   						},
   						'update' => function($url, $model) {
   							return Html::a('<span class="glyphicon glyphicon-pencil"></span>', 'index.php?r=song%2Fupdate&id='.$model->song_id, ['title' => 'Update']);
   						},
   						'delete' => function($url, $model) {
   							return Html::a('<span class="glyphicon glyphicon-trash"></span>', 'index.php?r=song%2Fdelete&id='.$model->song_id, ['title' => 'Delete', 'data-method' => 'post', 'data-confirm' => 'Are you sure?']);
   						}*/
            		],
   			],
        ],
        'rowOptions' => function($model, $key, $index, $grid)
        {
   				return ['data-location' => $model->path.'/'.$model->filename];
        },
    	]);
	?>
    
</div>

<audio id="player" controls="controls">
	<source id="mp3_src" src="" type="audio/mpeg" preload="none"/>
	Your browser does not support the audio element.
</audio>
