<?php 

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

?>

<div class="post">
<span><a href="index.php?r=song/view&id=<?php echo $model->song_id; ?>"><?= Html::encode($model->path.'/'.$model->filename) ?></a></span>
<br>
<a href="index.php?r=admin/replace&song_id=<?php echo $model->song_id; ?>">Replace</a>
</div>
<br>