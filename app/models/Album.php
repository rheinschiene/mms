<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "album".
 *
 * @property integer $album_id
 * @property string $album_name
 *
 * @property AlbumSong[] $albumSongs
 * @property Song[] $songs
 */
class Album extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'album';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['album_name'], 'required'],
            [['album_name'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'album_id' => 'Album ID',
            'album_name' => 'Album Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlbumSongs()
    {
        return $this->hasMany(AlbumSong::className(), ['album_id' => 'album_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSongs()
    {
        return $this->hasMany(Song::className(), ['song_id' => 'song_id'])->viaTable('album_song', ['album_id' => 'album_id']);
    }
}
