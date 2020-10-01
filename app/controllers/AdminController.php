<?php

namespace app\controllers;

use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;
use app\models\Song;
use app\models\Artist;
use app\models\Playlist;
use yii\data\ActiveDataProvider;

class AdminController extends \yii\web\Controller
{
    public function actionIndex($age = 1)
    {
    	
    	$results = array();
    	
    	$root_path = "/var/www/html";
    	$path = $root_path.'/web/data';
    	
    	
    	require_once($root_path . '/getid3/getid3.php');
    	
    	$files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path), \RecursiveIteratorIterator::SELF_FIRST);
    	
    	$query = Song::find()->where('path = :path AND filename = :filename');
    	
    	foreach($files AS $file)
    	{
    		
    		set_time_limit(5);
    		$extension = substr($file,strlen($file)-3,strlen($file));
    		
    		if($file->isFile() && $extension == "mp3" && round(((time()-$file->getCTime())/60/60/24)) <= $age)
    		{
    			$getID3 = new \getID3;
    			$ThisFileInfo = $getID3->analyze($file);
    			\getid3_lib::CopyTagsToComments($ThisFileInfo);
    			
    			if(isset($ThisFileInfo['comments_html']['artist'][0]))
    			{
    				$artist = $ThisFileInfo['comments_html']['artist'][0];
    			}
    			else
    			{
    				$artist = 'NULL';
    			}
    			
    			if(isset($ThisFileInfo['tags']['id3v2']['title'][0]))
    			{
    				$title = $ThisFileInfo['tags']['id3v2']['title'][0];
    			}
    			else
    			{
    				$title = 'NULL';
    			}
    			
    			if(isset($ThisFileInfo['playtime_string']))
    			{
    				$length = $ThisFileInfo['playtime_string'];
    			}
    			else
    			{
    				$length = 'NULL';
    			}
    			
    			if(isset($ThisFileInfo['audio']['bitrate']))
    			{
    				$bitrate = round($ThisFileInfo['audio']['bitrate']);
    			}
    			else
    			{
    				$bitrate = 'NULL';
    			}
    			
    			if(isset($ThisFileInfo['comments_html']['genre'][0]))
    			{
    				$genre = $ThisFileInfo['comments_html']['genre'][0];
    			}
    			else
    			{
    				$genre = 'NULL';
    			}
    			
    			if(isset($ThisFileInfo['comments_html']['year'][0]))
    			{
    				if(!filter_var($ThisFileInfo['comments_html']['year'][0],FILTER_VALIDATE_INT))
    				{
    					$year = 0;
    				}
    				else
    				{
    					$year = $ThisFileInfo['comments_html']['year'][0];
    				}
    			}
    			else
    			{
    				$year = 0;
    			}
    			
    			if(isset($ThisFileInfo['comments_html']['album'][0]))
    			{
    				$album = $ThisFileInfo['comments_html']['album'][0];
    			}
    			else
    			{
    				$album = 'NULL';
    			}
    			
    			$checksum = sha1_file($file);
    			
    			$relative_path = str_replace($root_path.'/web/','', $file->getPath());
    			
    			$query->addParams([':path' => $relative_path, ':filename' => $file->getFilename()]);
    			
    			if($query->exists())
    			{
    				//echo $relative_path.$file->getFilename()." vorhanden!<br>";
    			}
    			else
    			{
    				$song = new Song();
    				$song->title = $title;
    				$song->rating = 0;
    				$song->last_played = '1986-11-15 12:00:00';
    				$song->checksum = $checksum;
    				$song->year = $year;
    				$song->count_played = 0;
    				$song->length = $length;
    				$song->genre = $genre;
    				$song->path = $relative_path;
    				$song->bitrate = "$bitrate";
    				$song->filename = $file->getFilename();
    				$song->setArtist($artist);
    				
    				array_push($results, $relative_path.'/'.$file->getFilename()." not found!");
    				
    				if($song->save())
    				{
    					array_push($results, "Song added! Song_ID: ".$song->getPrimaryKey());
    					$song->setAlbum($album);
    				}
    				else
    				{
    					array_push($results,"Adding song to database failed!");
    					//print_r($song->getErrors());
    				}
    			}
    		}
    	}
    	
    	return $this->render('index', [
    			'results' => $results,
    	]);
    	
    }

    public function actionDuplicates()
    {
    	$results = array();
	    $query = Song::find()->all();
	    	
	    foreach($query AS $song_a)
	    {
	    	foreach($query AS $song_b)
	    	{
	    		if(($song_a->song_id != $song_b->song_id) && ($song_a->song_id > $song_b->song_id) && ($song_a->checksum == $song_b->checksum))
	    		{
	    			$temp = array(
	    					'song_a_id' => $song_a->song_id,
	    					'song_a_path' => $song_a->path,
	    					'song_a_filename' => $song_a->filename,
	    					'song_b_id' => $song_b->song_id,
	    					'song_b_path' => $song_b->path,
	    					'song_b_filename' => $song_b->filename,
	    			);
	    			array_push($results,$temp);
	    			break;
	    		}
	    	}
	    }
	    
	    return $this->render('duplicates', [
	    		'results' => $results,
	    ]);
	    
	}
    
    public function actionInvalid()
    {
    	$query = Song::find()->all();
    	
    	foreach($query AS $song)
    	{
    		$path = $song->path.'/'.$song->filename;
    		if(!file_exists('../../'.$path))
    		{
    			$song->valid = 0;
    			$song->save();
    		}
    		else
    		{
    			if($song->valid == 0)
    			{
    				$song->valid = 1;
    				$song->save();
    			}
    		}
    	}
    	
    	$dataProvider = new ActiveDataProvider([
    			'query' => Song::find()->where('valid = false'),
    	]);
    	
    	
    	return $this->render('invalid', [
    			'dataProvider' => $dataProvider,
    	]);
    }

	public function actionReplace($song_id = 0)
	{
		if($song_id > 0)
		{
			$query = Song::findOne(['song_id' => $song_id]);
			$results = $query->setReplace();
		}
		else
		{
			$results = array("Invalid SongID!");
		}
		
		return $this->render('replace', [
				'results' => $results,
		]);
	}
	
	public function actionExportRandom() {

		$query = Song::find();
		$query->where('path NOT LIKE "%Filmmusik%" AND path NOT LIKE "%Ayla%"');
		$query->orderBy(['last_played' => SORT_ASC]);
		$query->limit(100);
		$songs = $query->all();
		
		shuffle($songs);
		
		$date = date("Y-m-d");
		$pathRoot = "/var/www/html/";
		$pathDestination = $pathRoot . "export/$date";

		if(!file_exists($pathDestination)) {
			mkdir($pathDestination);
			chmod($pathDestination, 0777);
		}
		
		for($i=0; $i<count($songs)-75; $i++) {
			echo $i . " - " . $songs[$i]->filename . " - " . $songs[$i]->last_played . "<br>";
			if(copy($pathRoot . "web/" . $songs[$i]->path . "/" . $songs[$i]->filename, $pathDestination . "/" . $i . "_" . $songs[$i]->filename)) {
				$songs[$i]->last_played = date('Y-m-d G:i:s');
				$songs[$i]->save();
			}
		}
	}
	
	public function actionExportPlaylist($playlist_id) {

		$query = Song::find();
		$query->joinWith('playlists');
	    $query->where(['playlist.playlist_id' => $playlist_id]);
		$query->orderBy(['position' => SORT_ASC]);
		$songs = $query->all();
		
		$playlist = Playlist::findOne($playlist_id);
		$pathRoot = "/var/www/music/";
		$pathDestination = $pathRoot . "random_music/" . $playlist->playlist_name;

		if(!file_exists($pathDestination)) {
			mkdir($pathDestination);
		}
		
		foreach($songs as $key => $song) {
			echo $key . " - " . $song->filename . " - " . $song->last_played . "<br>";
			if(copy($pathRoot . $song->path . "/" . $song->filename, $pathDestination . "/" . $key . "_" . $song->filename)) {
				$song->last_played = date('Y-m-d G:i:s');
				$song->save();
			}
		}

	}
}
