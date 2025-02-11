<?php

namespace common\models\useraddress;
use common\models\country\Country;
use shop\entities\User\User;

use Yii;

/**
 * This is the model class for table "user_address".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $country_id
 * @property string $state
 * @property string $city
 * @property string $area
 * @property string $postal_code
 * @property string $address
 * @property integer $default
 * @property string $created_at
 * @property string $created_by
 * @property string $modified_at
 * @property string $modified_by
 * @property integer $is_deleted
 *
 * @property User $user
 * @property Country $country
 */
class UserAddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'country_id','state', 'city', 'area', 'postal_code','address','first_name','last_name','phone_number'], 'required'],
            [['user_id', 'country_id', 'default', 'created_at', 'created_by', 'modified_at', 'modified_by', 'is_deleted','phone_number'], 'integer'],
            [['state', 'city', 'area', 'postal_code', 'latitude', 'longitude'], 'string', 'max' => 256],
            [['address'], 'string', 'max' => 512],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'id']],
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
            'country_id' => 'Country ID',
            'state' => 'State',
            'city' => 'City',
            'area' => 'Area',
            'postal_code' => 'Postal Code',
            'address' => 'Address',
            'default' => 'Default',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'modified_at' => 'Modified At',
            'modified_by' => 'Modified By',
            'is_deleted' => 'Is Deleted',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
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
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'country_id']);
    }
}
