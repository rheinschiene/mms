<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SongSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="song-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'song_id') ?>

    <?= $form->field($model, 'artist_id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'filename') ?>

    <?= $form->field($model, 'path') ?>

    <?php // echo $form->field($model, 'rating') ?>

    <?php // echo $form->field($model, 'year') ?>

    <?php // echo $form->field($model, 'genre') ?>

    <?php // echo $form->field($model, 'last_played') ?>

    <?php // echo $form->field($model, 'count_played') ?>

    <?php // echo $form->field($model, 'checksum') ?>

    <?php // echo $form->field($model, 'length') ?>

    <?php // echo $form->field($model, 'bitrate') ?>

    <?php // echo $form->field($model, 'valid') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
