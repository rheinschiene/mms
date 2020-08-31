<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "playlist_song".
 *
 * @property integer $playlist_id
 * @property integer $song_id
 * @property integer $position
 *
 * @property Song $song
 * @property Playlist $playlist
 */
class PlaylistSong extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'playlist_song';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['playlist_id', 'song_id', 'position'], 'required'],
            [['playlist_id', 'song_id', 'position'], 'integer'],
            [['song_id'], 'exist', 'skipOnError' => true, 'targetClass' => Song::className(), 'targetAttribute' => ['song_id' => 'song_id']],
            [['playlist_id'], 'exist', 'skipOnError' => true, 'targetClass' => Playlist::className(), 'targetAttribute' => ['playlist_id' => 'playlist_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'playlist_id' => 'Playlist ID',
            'song_id' => 'Song ID',
            'position' => 'Position',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSong()
    {
        return $this->hasOne(Song::className(), ['song_id' => 'song_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaylist()
    {
        return $this->hasOne(Playlist::className(), ['playlist_id' => 'playlist_id']);
    }
}
