<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Playlist */

$this->title = 'Update Playlist: ' . $model->playlist_id;
$this->params['breadcrumbs'][] = ['label' => 'Playlists', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->playlist_id, 'url' => ['view', 'id' => $model->playlist_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="playlist-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
