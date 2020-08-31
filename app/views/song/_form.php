<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Song */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="song-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'artist_id')->textInput(['readonly' => true]) ?>
    
    <?= $form->field($model->artist, 'artist_name')->textInput(['readonly' => true])->label('<a href="index.php?r=artist%2Fupdate&id='.$model->artist_id.'">Artist Name</a>') ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'filename')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'path')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rating')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'year')->textInput() ?>

    <?= $form->field($model, 'genre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_played')->textInput() ?>

    <?= $form->field($model, 'count_played')->textInput() ?>

    <?= $form->field($model, 'checksum')->textInput(['maxlength' => true, 'readonly' => true]) ?>

    <?= $form->field($model, 'length')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bitrate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'valid')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
