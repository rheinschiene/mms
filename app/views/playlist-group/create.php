<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PlaylistGroup */

$this->title = 'Create Playlist Group';
$this->params['breadcrumbs'][] = ['label' => 'Playlist Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="playlist-group-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
