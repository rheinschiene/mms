<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PlaylistGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Playlist Groups';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="playlist-group-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Playlist Group', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],

            'group_id',
            'group_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
