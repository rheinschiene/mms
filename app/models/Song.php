<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "song".
 *
 * @property integer $song_id
 * @property integer $artist_id
 * @property string $title
 * @property string $filename
 * @property string $path
 * @property string $rating
 * @property integer $year
 * @property string $genre
 * @property string $last_played
 * @property integer $count_played
 * @property string $checksum
 * @property string $length
 * @property string $bitrate
 * @property integer $valid
 *
 * @property AlbumSong[] $albumSongs
 * @property Album[] $albums
 * @property PlaylistSong[] $playlistSongs
 * @property Playlist[] $playlists
 * @property Artist $artist
 */
class Song extends \yii\db\ActiveRecord
{
	public $playlist_id = 0;
	public $temp_path = 0;
	public $temp_filename = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'song';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['artist_id', 'title', 'filename', 'path', 'rating', 'year', 'genre', 'count_played', 'checksum', 'length', 'bitrate'], 'required'],
            [['artist_id', 'year', 'count_played', 'valid'], 'integer'],
            [['rating'], 'number'],
            [['last_played'], 'safe'],
            [['title', 'path'], 'string', 'max' => 100],
            [['filename'], 'string', 'max' => 150],
            [['genre'], 'string', 'max' => 50],
            [['checksum'], 'string', 'max' => 40],
            [['length', 'bitrate'], 'string', 'max' => 10],
            [['artist_id'], 'exist', 'skipOnError' => true, 'targetClass' => Artist::className(), 'targetAttribute' => ['artist_id' => 'artist_id']],
        	//[['artistName'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'song_id' => 'Song ID',
            'artist_id' => 'Artist ID',
            'title' => 'Title',
            'filename' => 'Filename',
            'path' => 'Path',
            'rating' => 'Rating',
            'year' => 'Year',
            'genre' => 'Genre',
            'last_played' => 'Last Played',
            'count_played' => 'Count Played',
            'checksum' => 'Checksum',
            'length' => 'Length',
            'bitrate' => 'Bitrate',
            'valid' => 'Valid',
        	'artistName' => 'Artist Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlbumSongs()
    {
        return $this->hasMany(AlbumSong::className(), ['song_id' => 'song_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlbums()
    {
        return $this->hasMany(Album::className(), ['album_id' => 'album_id'])->viaTable('album_song', ['song_id' => 'song_id']);
    }
    
    public function getAlbumName()
    {
    	if(count($this->albums) > 0)
    	{
    		return $this->albums[0]->album_name;
    	}
    	else
    	{
    		return "No Album!";
    	}
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaylistSongs()
    {
        return $this->hasMany(PlaylistSong::className(), ['song_id' => 'song_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaylists()
    {
        return $this->hasMany(Playlist::className(), ['playlist_id' => 'playlist_id'])->viaTable('playlist_song', ['song_id' => 'song_id']);
    }
    
    public function getPlayListPosition()
    {
    	$position = 0;
    	
    	for ($i=0;$i<count($this->playlistSongs);$i++)
    	{
    		if($this->playlist_id == $this->playlistSongs[$i]->playlist_id)
    		{
    			$position = $this->playlistSongs[$i]->position;
    		}
    	}
    	return $position;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArtist()
    {
        return $this->hasOne(Artist::className(), ['artist_id' => 'artist_id']);
    }
    
    public function getArtistName()
    {
    	return $this->artist->artist_name;
    }
    
    public function setSongPlayed()
    {
    	$this->count_played++;
    	$this->last_played = date('Y-m-d G:i:s');
    	$this->save();
    }
    
    public function setRateUp()
    {
    	$this->rating++;
    	
    	if($this->save())
    	{
    		return 1;
    	}
    	else
    	{
    		return 0;
    	}
    }
    
    public function setRateDown()
    {
    	$this->rating--;
    	
    	if($this->save())
    	{
    		return 1;
    	}
    	else
    	{
    		return 0;
    	}
    }
    
    public function beforeDelete()
    {
    	$this->temp_path = $this->path;
    	$this->temp_filename = $this->filename;
    	PlaylistSong::deleteAll('song_id = :song_id', ['song_id' => $this->song_id]);
    	AlbumSong::deleteAll('song_id = :song_id', ['song_id' => $this->song_id]);
    		
    	return parent::beforeDelete();
    }
    
    public function afterDelete()
    {
    	if($this->valid)
    	{
	    	$path = $this->temp_path.'/'.$this->temp_filename;
	    	unlink('/var/www/html/web/'.$path);
	    	return parent::afterDelete();
    	}
    }
    
    public function setArtist($artist)
    {
    	$query = Artist::find()->where('artist_name = :artist_name')->addParams([':artist_name' => $artist]);
    	
    	if($query = $query->one())
    	{
    		$this->artist_id = $query->artist_id;
    	}
    	else
    	{
    		$query = new Artist();
    		$query->artist_name = $artist;
    			
    		if($query->save())
    		{
    			$this->artist_id = $query->getPrimaryKey();
    		}
    		else
    		{
    			return false;
    		}
    	}
    }
    
    public function setAlbum($album)
    {
    	$query = Album::find()->where('album_name = :album_name')->addParams([':album_name' => $album]);
    	if($query = $query->one())
    	{
    		$this->link('albums', $query);
    	}
    	else
    	{
    		$new_album = new Album();
    		$new_album->album_name = $album;
    		$new_album->save();
    		$this->link('albums', $new_album);

    	}
    }
    
    public function setReplace()
    {
    	$results = array();
    	$query = Song::find()->where('checksum = "'.$this->checksum.'" AND song_id != '.$this->song_id.' AND valid = 1')->one();
    	if(count($query) == 1)
    	{
    		array_push($results,$this->song_id.' => '.$query->song_id);
    		array_push($results,$this->path.' => '.$query->path);
    		array_push($results,$this->filename.' => '.$query->filename);
    		
    		$this->path = $query->path;
    		$this->filename = $query->filename;
    		$this->valid = 1;
    		
    		if($this->save())
    		{
    			array_push($results,$this->song_id.' updated...');
    			// Valid wird auf 0 gesetzt, damit die Datei
    			// nicht beim Song::afterDelete() gelöscht wird.
    			$query->valid = 0;
    			if($query->save())
    			{
    				array_push($results,$query->song_id.' updated...');
    				if($query->delete())
    				{
    					array_push($results,'...deleted');
    				}
    			}
    			
    		}
    	}
    	else
    	{
    		echo array_push($results,"No songs found!");
    	}
    	
    	return $results;
    	
    }
}
