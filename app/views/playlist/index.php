<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\PlaylistGroup;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PlaylistSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Playlists';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="playlist-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Playlist', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'playlist_id',
            //'group_id',
        	[
        		'attribute' => 'groupName',
        		'filter' => ArrayHelper::map(PlaylistGroup::find()->all(), 'group_name', 'group_name'),
        	],
        	[
        		'attribute' => 'playlist_name',
        		'format' => 'raw',
        		'value' => function($data) {
        			return Html::a($data['playlist_name'],'index.php?r=song/index&playlist_id='.$data['playlist_id']);
    			},
    		],
    		/*[
	    		'attribute' => 'songs',
	    		'value' => function($model)
	    		{
	    			return implode(', ', ArrayHelper::map($model->songs, 'song_id', 'title'));
	    		}
    		],*/
    		[
    			'attribute' => 'songLastPlayed',
    			'label' => 'Playlist LastPlayed',
    			'format' => 'text',
    			'value' => function($data) {
    				return substr($data['songLastPlayed'], 0, 4);
    			}
    		],

            ['class' => 'yii\grid\ActionColumn',
            		'template' => '{export}&nbsp;{view}&nbsp;{update}&nbsp;{delete}',
            		'buttons' => [
            			'export' => function($url, $model){
            				return Html::a('<span class="glyphicon glyphicon-export"></span>', 'index.php?r=admin%2Fexport-playlist&playlist_id=' . $model->playlist_id, ['title' => 'Export to Folder']);
            			},
            		],
   			],
        ],
    ]); ?>
</div>
