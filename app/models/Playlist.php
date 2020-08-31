<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "playlist".
 *
 * @property integer $playlist_id
 * @property integer $group_id
 * @property string $playlist_name
 *
 * @property PlaylistGroup $group
 * @property PlaylistSong[] $playlistSongs
 * @property Song[] $songs
 */
class Playlist extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'playlist';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'playlist_name'], 'required'],
            [['group_id'], 'integer'],
            [['playlist_name'], 'string', 'max' => 50],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlaylistGroup::className(), 'targetAttribute' => ['group_id' => 'group_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'playlist_id' => 'Playlist ID',
            'group_id' => 'Group ID',
            'playlist_name' => 'Playlist Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(PlaylistGroup::className(), ['group_id' => 'group_id']);
    }
    
    public function getGroupName()
    {
    	return $this->group->group_name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaylistSongs()
    {
        return $this->hasMany(PlaylistSong::className(), ['playlist_id' => 'playlist_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSongs()
    {
        return $this->hasMany(Song::className(), ['song_id' => 'song_id'])->viaTable('playlist_song', ['playlist_id' => 'playlist_id']);
    }
    
    public function getSongLastPlayed()
    {
    	return Playlist::find()
    	->joinWith('songs')
    	->where('playlist.playlist_id = :playlist_id')
    	->addParams([':playlist_id' => $this->playlist_id])
    	->average('last_played');
    }
    
    public function beforeDelete()
    {
    	if(parent::beforeDelete())
    	{
    		PlaylistSong::deleteAll('playlist_id = :playlist_id', ['playlist_id' => $this->playlist_id]);
    		return true;
    	}
    	else
    	{
    		return false;
    	}
    }
}
