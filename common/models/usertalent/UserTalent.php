<?php

namespace common\models\usertalent;
use shop\entities\User\User;
use common\models\industry\Industry;
use common\models\talentmaster\TalentMaster;
use common\models\djgenre\DjGenre;
use common\models\instrument\Instrument;
use common\models\instrumentspecification\InstrumentSpecification;
use common\models\musicgenre\MusicGenre;
use Yii;

/**
 * This is the model class for table "user_talent".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $industry_id
 * @property integer $talent_id
 * @property string $gender
 * @property integer $dj_genre_id
 * @property integer $instrument_id
 * @property integer $instrument_spec_id
 * @property integer $music_genre_id
 * @property string $created_at
 * @property string $created_by
 * @property string $modified_at
 * @property string $modified_by
 * @property integer $is_deleted
 *
 * @property User $user
 * @property Industry $industry
 * @property TalentMaster $talent
 * @property DjGenre $djgenre
 * @property Instrument $instrument
 * @property InstrumentSpecification $instrumentspecification
 * @property MusicGenre $musicgenre
 */
class UserTalent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_talent';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'industry_id',], 'required'],
            [['user_id', 'industry_id', 'talent_id', 'dj_genre_id', 'instrument_id', 'instrument_spec_id', 'music_genre_id', 'created_at', 'created_by', 'modified_at', 'modified_by', 'is_deleted'], 'integer'],
            [['gender'], 'string'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['industry_id'], 'exist', 'skipOnError' => true, 'targetClass' => Industry::className(), 'targetAttribute' => ['industry_id' => 'id']],
            [['talent_id'], 'exist', 'skipOnError' => true, 'targetClass' => TalentMaster::className(), 'targetAttribute' => ['talent_id' => 'id']],
            [['dj_genre_id'], 'exist', 'skipOnError' => true, 'targetClass' => DjGenre::className(), 'targetAttribute' => ['dj_genre_id' => 'id']],
            [['instrument_id'], 'exist', 'skipOnError' => true, 'targetClass' => DjGenre::className(), 'targetAttribute' => ['instrument_id' => 'id']],
            [['instrument_spec_id'], 'exist', 'skipOnError' => true, 'targetClass' => DjGenre::className(), 'targetAttribute' => ['instrument_spec_id' => 'id']],
            [['music_genre_id'], 'exist', 'skipOnError' => true, 'targetClass' => DjGenre::className(), 'targetAttribute' => ['music_genre_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'industry_id' => 'Industry ID',
            'talent_id' => 'Talent ID',
            'gender' => 'Gender',
            'dj_genre_id' => 'Dj Genre ID',
            'instrument_id' => 'Instrument ID',
            'instrument_spec_id' => 'Instrument Spec ID',
            'music_genre_id' => 'Music Genre ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'modified_at' => 'Modified At',
            'modified_by' => 'Modified By',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMusicgenre()
    {
        return $this->hasOne(MusicGenre::className(), ['id' => 'music_genre_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstrumentspecification()
    {
        return $this->hasOne(InstrumentSpecification::className(), ['id' => 'instrument_spec_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstrument()
    {
        return $this->hasOne(Instrument::className(), ['id' => 'instrument_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDjgenre()
    {
        return $this->hasOne(DjGenre::className(), ['id' => 'dj_genre_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIndustry()
    {
        return $this->hasOne(Industry::className(), ['id' => 'industry_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTalent()
    {
        return $this->hasOne(TalentMaster::className(), ['id' => 'talent_id']);
    }
}
