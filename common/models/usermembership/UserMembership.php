<?php
namespace common\models\usermembership;

use Yii;
use shop\entities\User\User;
use common\models\membership\Membership;
/**
 * This is the model class for table "user_membership".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $membership_id
 * @property string $status
 * @property string $category
 * @property string $created_at
 * @property string $created_by
 * @property string $modified_at
 * @property string $modified_by
 * @property integer $is_deleted
 *
 * @property User $user
 * @property Membership $membership
 */
class UserMembership extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'user_membership';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_id', 'membership_id'], 'required'],
            [['user_id', 'membership_id', 'created_at', 'created_by', 'modified_at', 'modified_by', 'is_deleted'], 'integer'],
            [['status', 'category'], 'string'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['membership_id'], 'exist', 'skipOnError' => true, 'targetClass' => Membership::className(), 'targetAttribute' => ['membership_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'membership_id' => 'Membership ID',
            'status' => 'Status',
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
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembership() {
        return $this->hasOne(Membership::className(), ['id' => 'membership_id']);
    }

}
