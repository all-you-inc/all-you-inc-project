<?php
namespace shop\entities\User;

use shop\entities\EventTrait;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use shop\entities\AggregateRoot;
use shop\entities\User\events\UserSignUpConfirmed;
use shop\entities\User\events\UserSignUpRequested;
use common\models\usersubscription\UserSubscription;
use common\models\usertalent\UserTalent;
use common\models\useraddress\UserAddress;
use common\models\userprofileimage\UserProfileImage;
use common\models\usersquareinfo\UsersSquareInfo;
use shop\entities\Shop\Order\Order;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use common\models\membership\Membership;
use common\models\membership\MsItems;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use common\models\country\Country;
/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $email_confirm_token
 * @property string $phone
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $name
 * @property string $city
 * @property string $state
 * @property string $country
 * @property string $referral_code
 * 
 * @property Network[] $networks
 * @property WishlistItem[] $wishlistItems
 * @property UserSubscription[] $userSubscription
 * @property Country $userCountry
 * @property UserTalent $userTalent
 * @property UserAddress[] $userAddress
 * @property Order[] $order
 * @property UserProfileImage[] $userProfileImage 
 * @property UsersSquareInfo[] $usersSquareInfo
 */
class User extends ActiveRecord implements AggregateRoot
{
    use EventTrait;

    const STATUS_WAIT = 0;
    const STATUS_ACTIVE = 10;

    public static function create(string $username, string $email, string $phone, string $password): self
    {
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->phone = $phone;
        $user->setPassword(!empty($password) ? $password : Yii::$app->security->generateRandomString());
        $user->created_at = time();
        $user->status = self::STATUS_ACTIVE;
        $user->auth_key = Yii::$app->security->generateRandomString();
        $user->referral_code = Yii::$app->security->generateRandomString();
        return $user;
    }

    public function edit(string $username, string $email, string $phone): void
    {
        $this->username = $username;
        $this->email = $email;
        $this->phone = $phone;
        $this->updated_at = time();
    }

    public function editProfile(string $email, string $phone): void
    {
        $this->email = $email;
        $this->phone = $phone;
        $this->updated_at = time();
    }

    public static function requestSignup(string $username, string $email, string $name, string $password, string $membership_id): self
    {
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->name = $name;
        $user->setPassword($password);
        $user->created_at = time();
        if (\Yii::$app->params['production']) {
            $user->status = self::STATUS_WAIT;
            $user->email_confirm_token = Yii::$app->security->generateRandomString();            
        } else {
            $user->status = self::STATUS_ACTIVE;
            $user->email_confirm_token = null;
        }
        $user->generateAuthKey();
        $user->generateReferralCode();
        $user->recordEvent(new UserSignUpRequested($user));
        $user->membership_id = $membership_id;
        if ($user->membership_id == Membership::Promoter) {
            $user->tiers_payout_id = 1;
        }
        return $user;
    }

    public function confirmSignup(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException('User is already active.');
        }
        $this->status = self::STATUS_ACTIVE;
        $this->email_confirm_token = null;
        $this->recordEvent(new UserSignUpConfirmed($this));
    }

    public static function signupByNetwork($network, $identity): self
    {
        $user = new User();
        $user->created_at = time();
        $user->status = self::STATUS_ACTIVE;
        $user->generateAuthKey();
        $user->generateReferralCode();
        $user->networks = [Network::create($network, $identity)];
        return $user;
    }

    public function attachNetwork($network, $identity): void
    {
        $networks = $this->networks;
        foreach ($networks as $current) {
            if ($current->isFor($network, $identity)) {
                throw new \DomainException('Network is already attached.');
            }
        }
        $networks[] = Network::create($network, $identity);
        $this->networks = $networks;
    }

    public function addToWishList($productId): void
    {
        $items = $this->wishlistItems;
        foreach ($items as $item) {
            if ($item->isForProduct($productId)) {
                throw new \DomainException('Item is already added.');
            }
        }
        $items[] = WishlistItem::create($productId);
        $this->wishlistItems = $items;
    }

    public function removeFromWishList($productId): void
    {
        $items = $this->wishlistItems;
        foreach ($items as $i => $item) {
            if ($item->isForProduct($productId)) {
                unset($items[$i]);
                $this->wishlistItems = $items;
                return;
            }
        }
        throw new \DomainException('Item is not found.');
    }

    public function requestPasswordReset(): void
    {
        if (!empty($this->password_reset_token) && self::isPasswordResetTokenValid($this->password_reset_token)) {
            throw new \DomainException('Password resetting is already requested.');
        }
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function resetPassword($password): void
    {
        if (empty($this->password_reset_token)) {
            throw new \DomainException('Password resetting is not requested.');
        }
        $this->setPassword($password);
        $this->password_reset_token = null;
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function getNetworks(): ActiveQuery
    {
        return $this->hasMany(Network::className(), ['user_id' => 'id']);
    }

    public function getWishlistItems(): ActiveQuery
    {
        return $this->hasMany(WishlistItem::class, ['user_id' => 'id']);
    }

    public function getUserSubscription()
    {
        return $this->hasMany(UserSubscription::className(), ['user_id' => 'id']);
    }

    public function getUserTalent()
    {
        return $this->hasMany(UserTalent::className(), ['user_id' => 'id']);
    }

    public function getUserCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'country']);
    }

    public function getUserAddress()
    {
        return $this->hasMany(UserAddress::className(), ['user_id' => 'id']);
    }

    public function getOrder()
    {
        return $this->hasMany(Order::className(), ['user_id' => 'id']);
    }

    public function getUserProfileImage()
    {
        return $this->hasMany(UserProfileImage::className(), ['user_id' => 'id']);
    }

    public function getUsersSquareInfo()
    {
        return $this->hasMany(UsersSquareInfo::className(), ['user_id' => 'id']);
    }

    public function checkActiveTalentMemberShip(){

      $subscriptions =  UserSubscription::find()->where([
                                'type'=>"membership",
                                'status' => "active",
                                'is_deleted' => 0,
                                'user_id' => $this->id
                            ])->all();
       
 

      foreach($subscriptions as $subscription){
         if( $subscription->ref_id == 1 || $subscription->ref_id == 2 
             || $subscription->ref_id == 6 || $subscription->ref_id == 7 )
             return true; 
      }                          
      return false;

    }

    public function canUpdateProfile()
    {
        
//        $isPlanTalent = $this->userMembership->membership->id == 1;
        $isPlanTalent = $this->checkActiveTalentMemberShip();
    
        $isTalentSet = $this->userTalent == NULL;

        if($isPlanTalent && $isTalentSet)
        {
            return true;
        }
        return false;
    }

    public function getSubscription($type) {
        $result = [];
        if ($this->userSubscription) {
            foreach ($this->userSubscription as $subscription) {
                if ($subscription->type == $type && $subscription->status == 'active') {
                    $result[] = $subscription;
                }
            }
        }
        return $result;
    }

    public function hasAddress()
    {
         if($this->userAddress == NULL)
         {
            return true;
         }
         return false;
    }

    public function isUserSubscribed()
    {
        if($this->getSubscription('membership')){
            return TRUE;
        }
        return FALSE;
    }
    public function getPaymentType()
    {
        $membership_id = $this->getMembershipId();
         if($membership_id == Membership::Talent || $membership_id == Membership::TalentWithProduct)
        {
           return 'basic';
        }
        return 'free';
       
    }

    public function getMembershipId()
    {
        if(isset($this->getSubscription('membership')[0]->ref_id))
        {
            return $this->getSubscription('membership')[0]->ref_id;
        }
        return false;
    }

    public function isTalent()
    {
        $membership_id = $this->getMembershipId();        
        if($membership_id == Membership::Talent)
        {
            return true;
        }
        return false;
    }

    public function canShowBothTalent()
    {
        $membership_id = $this->getMembershipId();        
        if($membership_id == Membership::Talent || $membership_id == Membership::TalentWithProduct
        || $membership_id == Membership::FreeTalent || $membership_id == Membership::FreeTalentWithProduct)
        {
            return true;
        }
        return false;
    }

    public function isTalentWithProduct()
    {
        $membership_id = $this->getMembershipId();        
        if($membership_id == Membership::TalentWithProduct || $membership_id == Membership::FreeTalentWithProduct)
        {
            return true;
        }
        return false;
    }

    public function isPromoter()
    {
        $membership_id = $this->getMembershipId();        
        if($membership_id == Membership::Promoter)
        {
            return true;
        }
        return false;
    }

    public function getSubscribedItems(){
        $AllItems = $this->userSubscription;
        $MsItems = array();
        foreach($AllItems as $item)
        {
            if($item->status == 'active')
            {
                $type = $item->type;
                if($type == 'membership')
                {
                    if($item->ref_id == Membership::FreeTalent |
                        $item->ref_id == Membership::FreeTalentWithProduct)
                    {
                        $getItems = MsItems::find()
                                    ->where(['membership_id' => $item->ref_id,
                                             'type' => 'free'])
                                    ->all();

                        foreach($getItems as $singleItem)
                        {
                            array_push($MsItems,$singleItem);
                        }
                    }
                    else if($item->ref_id == Membership::Talent ||
                            $item->ref_id == Membership::TalentWithProduct ||
                            $item->ref_id == Membership::Promoter)
                    {
                        $getItems = MsItems::find()
                                    ->where(['membership_id' => $item->ref_id,
                                             'type' => 'basic'])
                                    ->all();
                                    
                        foreach($getItems as $singleItem)
                        {
                            array_push($MsItems,$singleItem);
                        }
                    }
                }
                else if($type == 'addons')
                {
                    $getItems = MsItems::find()
                                    ->where(['id' => $item->ref_id,
                                             'type' => 'addons'])
                                    ->all();
                                    
                    foreach($getItems as $singleItem)
                    {
                        array_push($MsItems,$singleItem);
                    }
                }
            }
        }
        return $MsItems;
    }

    public function getTotalOrders()
    {
        return $this->order == NULL ? 0 : count($this->order);
    }

    public function getTotalSalesAmount($talentId = null,$productId = null)
    {
        $conditionArr = 'shop_products.created_by ='.\Yii::$app->user->id;
        if($talentId != null){
            $conditionArr .= ' AND shop_products.talent_id='.$talentId;
        }
        if($productId != null){
            $conditionArr .= ' AND shop_order_items.product_id='.$productId;
        }
        $orders = Order::find()
        ->leftJoin('shop_order_items' , 'shop_order_items.order_id = shop_orders.id')
        ->leftJoin('shop_products' , 'shop_products.id = shop_order_items.product_id')
        ->andWhere($conditionArr)
        ->all();
        
        $totalAmount = 0;

        if($orders != NULL){
            
            foreach($orders as $order)
            {
                $totalAmount += $order->getSalesCost($talentId);
            }
            return $totalAmount;
        }
        return $totalAmount;
    }

    // TRUE IF NEED TO ADD
    public function isUserNeedToAddInSquare()
    {
        return $this->square_cust_id == NULL;
    }

    // ONLY FOR PLAN/USER-SUBSCRIPTION SCREEN 
    // TRUE IF NEED
    public function isUserCardNeedToAdd($plan_id)
    {
        if($plan_id == Membership::Talent || $plan_id == Membership::TalentWithProduct 
            || $plan_id == Membership::Promoter)
        {
            return true;
        }
        return false;
    }

    // FALSE IF NEED TO ADD
    public function isUserCardEXist()
    {
        return $this->usersSquareInfo != null;
    }

    public function talentList() : array
    {
        $query = new Query;
        $query->select(['user_talent.id','talent_master.name'])  
        ->from('user_talent')
        ->join(	'INNER JOIN','talent_master','talent_master.id = user_talent.talent_id')
        ->where(['user_id' => $this->id]);

        $command = $query->createCommand();
        $data = $command->queryAll();
        if($data != null){
            return ArrayHelper::map($data, 'id', 'name');
        }
        return [];
    }

    //return null if all card is inactive
    //return cardId if one card is active
    public function getActiveCard(){
        $cards = $this->usersSquareInfo;
        if($cards != null)
        {
            foreach($cards as $card){
                if($card->status == 1){
                    return $card->card_id;
                }
            }
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => SaveRelationsBehavior::className(),
                'relations' => ['networks', 'wishlistItems'],
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    private function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    private function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
    
    /**
     * Generates "remember me" authentication key
     */
    private function generateReferralCode()
    {
        $this->referral_code = Yii::$app->security->generateRandomString();
    }
    
    public static function getNotificationText()
    {
        return 'User Notification Text';
    }
}
