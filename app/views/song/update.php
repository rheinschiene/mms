<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Song */

$this->title = 'Update Song: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Songs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->song_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="song-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
