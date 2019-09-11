<?php

namespace common\models\tierspayout;

use Yii;

/**
 * This is the model class for table "tiers_payout".
 *
 * @property integer $id
 * @property string $title
 * @property string $key
 * @property integer $percent
 * @property string $team
 * @property string $created_at
 * @property string $created_by
 * @property string $modified_at
 * @property string $modified_by
 * @property integer $is_deleted
 */
class TiersPayout extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tiers_payout';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'key', 'percent', 'team'], 'required'],
            [['percent', 'created_at', 'created_by', 'modified_at', 'modified_by', 'is_deleted'], 'integer'],
            [['title', 'key', 'team'], 'string', 'max' => 128],
            [['key'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'key' => 'Key',
            'percent' => 'Percent',
            'team' => 'Team',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'modified_at' => 'Modified At',
            'modified_by' => 'Modified By',
            'is_deleted' => 'Is Deleted',
        ];
    }
}
