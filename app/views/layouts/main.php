<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'MMS v2',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
        	['label' => 'Songs', 'url' => ['/song/index']],
        	['label' => 'Random', 'url' => ['/song/random']],
        	['label' => 'Playlists', 'url' => ['/playlist/index']],
        	['label' => 'Groups', 'url' => ['/playlist-group/index']],
        	[
        		'label' => 'Admin',
        		'items' => [
        				//'<li class="divider"></li>',
        				//'<li class="dropdown-header">Admin</li>',
        				['label' => 'Index', 'url' => ['/admin/index']],
        				['label' => 'Duplicates', 'url' => ['/admin/duplicates']],
        				['label' => 'Invalid', 'url' => ['/admin/invalid']],
					['label' => 'RandomExport', 'url' => ['/admin/export-random']],
        		],
        	],
        	
        	/*
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )*/
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
        <?php 
        	if($this->title == "Songs")
        	{
        		echo "<p>";
        		echo Html::submitButton('End Playback',['id' => 'end_playback']);
        		echo Html::submitButton('Next',['id' => 'next']);
        		echo "</p>";
        	}
        ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">MMS <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
