<?php

namespace  common\models\membership;

use Yii;
use app\common\models\usermembership\UserMembership;
/**
 * This is the model class for table "membership".
 *
 * @property integer $id
 * @property string $title
 * @property integer $level
 * @property string $price
 * @property string $status
 * @property string $description
 * @property string $category
 * @property string $created_at
 * @property string $created_by
 * @property string $modified_at
 * @property string $modified_by
 * @property integer $is_deleted
 *
 * @property UserMembership[] $userMemberships
 */
class Membership extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'membership';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['level', 'created_at', 'created_by', 'modified_at', 'modified_by', 'is_deleted'], 'integer'],
            [['status', 'description', 'category'], 'string'],
            [['title'], 'string', 'max' => 256],
            [['price'], 'string', 'max' => 64],
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
            'level' => 'Level',
            'price' => 'Price',
            'status' => 'Status',
            'description' => 'description',
            'category' => 'Category',
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
    public function getUserMemberships()
    {
        return $this->hasMany(UserMembership::className(), ['membership_id' => 'id']);
    }
}
