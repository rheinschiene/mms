<?php
$this->title = 'Admin';
$this->params['breadcrumbs'][] = $this->title;
?>

<h4>Searching for duplicate files...</h4>

<?php 
echo "<ul>";
foreach($results AS $result)
{
	echo "<li>SongID: ".$result['song_a_id']." / ".$result['song_b_id'];
		echo "<ul>";
			echo "<li>";
			echo '<a href=index.php?r=song/view&id='.$result['song_a_id'].'>'.$result['song_a_path'].'/'.$result['song_a_filename'].'</a>';
			echo '</li>';
			echo "<li>";
			echo '<a href=index.php?r=song/view&id='.$result['song_b_id'].'>'.$result['song_b_path'].'/'.$result['song_b_filename'].'</a>';
			echo '</li>';
		echo "</ul>";
	echo "</li>";
}
echo "</ul>";
?>
