<?php

use yii\widgets\ListView;
use yii\base\Widget;

echo ListView::widget([
		'dataProvider' => $dataProvider,
		'itemView' => '_invalid',
]);

?>