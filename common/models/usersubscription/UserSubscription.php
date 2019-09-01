<?php

namespace common\models\usersubscription;

use shop\entities\User\User;
use Yii;

/**
 * This is the model class for table "user_subscription".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $type
 * @property integer $ref_id
 * @property string $status
 * @property string $category
 * @property string $last_billing_date
 * @property string $created_at
 * @property string $created_by
 * @property string $modified_at
 * @property string $modified_by
 * @property integer $is_deleted
 *
 * @property User $user
 */
class UserSubscription extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'user_subscription';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_id'], 'required'],
            [['user_id', 'ref_id', 'last_billing_date', 'created_at', 'created_by', 'modified_at', 'modified_by', 'is_deleted'], 'integer'],
            [['type', 'status', 'category'], 'string'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'type' => 'Type',
            'ref_id' => 'Ref ID',
            'status' => 'Status',
            'category' => 'Category',
            'last_billing_date' => 'Last Billing Date',
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

}
