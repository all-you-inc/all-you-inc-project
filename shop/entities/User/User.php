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
use shop\entities\Shop\Order\Order;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

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
 * 
 * @property Network[] $networks
 * @property WishlistItem[] $wishlistItems
 * @property UserSubscription[] $userSubscription
 * @property UserTalent $userTalent
 * @property UserAddress[] $userAddress
 * @property Order[] $order
 * @property UserProfileImage[] $userProfileImage 
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

    public static function requestSignup(string $username, string $email, string $name, string $password): self
    {
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->name = $name;
        $user->setPassword($password);
        $user->created_at = time();
        $user->status = self::STATUS_WAIT;
        $user->email_confirm_token = Yii::$app->security->generateRandomString();
        $user->generateAuthKey();
        $user->recordEvent(new UserSignUpRequested($user));
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
        return $this->hasOne(UserTalent::className(), ['user_id' => 'id']);
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

    public function canUpdateProfile()
    {
//        $isPlanTalent = $this->userMembership->membership->id == 1;
        $isPlanTalent = $this->userSubscription[0]->ref_id == 1;
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
                if ($subscription->type == $type) {
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

    public function canShowTalent()
    {
//        $isPlanTalent = $this->userMembership->membership->id == 1;
        $isPlanTalent = $this->userSubscription[0]->ref_id == 1;        
        if($isPlanTalent)
        {
            return true;
        }
        return false;
    }

    public function getTotalOrders()
    {
        
        if($this->order == NULL)
        {
            return 0;
        }
        else
        {
            return count($this->order);
        }
    }

    public function getTotalSalesAmount()
    {
        $orders = Order::find()
        ->leftJoin('shop_order_items' , 'shop_order_items.order_id = shop_orders.id')
        ->leftJoin('shop_products' , 'shop_products.id = shop_order_items.product_id')
        ->andWhere(['shop_products.created_by' => \Yii::$app->user->id])
        ->all();
 
        if($orders != NULL){
            foreach($orders as $order)
            {
                $totalAmount += $order->getSalesCost();
            }
            return $totalAmount;
        }
        return 0;
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
}
