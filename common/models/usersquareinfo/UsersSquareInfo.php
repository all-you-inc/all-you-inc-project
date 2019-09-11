<?php

namespace common\models\usersquareinfo;

use Yii;
use shop\entities\User\User;
/**
 * This is the model class for table "users_square_info".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $card_id
 *
 * @property User $user
 */
class UsersSquareInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users_square_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'card_id'], 'required'],
            [['user_id'], 'integer'],
            [['card_id'], 'string', 'max' => 355],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'card_id' => 'Card ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }


    public static function addNewCard($cardId){
        $newCard = new UsersSquareInfo;
        $newCard->user_id = \Yii::$app->user->id;
        $newCard->card_id = $cardId;
        $newCard->status = 1;
        if($newCard->save()){
            self::inActiveCards($newCard);
            return true;
        }
        return false;
    }

    public static function changeActiveCard($sourceId){
        $card = UsersSquareInfo::find()->where(['card_id' => $sourceId])->one();
        if($card != null) {
            $card->status = 1;
            $card->save();
            self::inActiveCards($card);
            return true;
        }
        return false;
    }

    private static function inActiveCards($card) : void {
        $allCards = UsersSquareInfo::find()
            ->where(['user_id' => \Yii::$app->user->id])
            ->andWhere(['<>', 'id', $card->id])
            ->all();
        foreach($allCards as $card){
            $card->status = 0;
            $card->save();
        }
    }
}
