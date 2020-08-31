<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PlaylistGroup */

$this->title = 'Update Playlist Group: ' . $model->group_id;
$this->params['breadcrumbs'][] = ['label' => 'Playlist Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->group_id, 'url' => ['view', 'id' => $model->group_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="playlist-group-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
