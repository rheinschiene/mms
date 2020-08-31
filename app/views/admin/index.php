<?php
$this->title = 'Admin';
$this->params['breadcrumbs'][] = $this->title;
?>

<h4>Searching for new files...</h4>

<?php 
foreach($results AS $result)
{
	echo $result.'<br>';
}
?>
