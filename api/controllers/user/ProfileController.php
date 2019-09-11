<?php

namespace api\controllers\user;

use Yii;
use yii\helpers\Url;
use yii\rest\Controller;
use api\helpers\DataHelper;
use api\helpers\DateHelper;
use shop\entities\User\User;
use shop\helpers\UserHelper;
use shop\services\RoleManager;
use shop\forms\auth\SignupForm;
use common\services\DjGenreService;
use common\services\IndustryService;
use common\models\membership\MsItems;
use shop\dispatchers\EventDispatcher;
use shop\repositories\UserRepository;
use shop\services\TransactionManager;
use shop\useCases\auth\SignupService;
use common\services\DjGenreDefinition;
use common\services\InstrumentService;
use common\services\MusicGenreService;
use common\services\IndustryDefinition;
use common\services\UserAddressService;
use common\models\membership\Membership;
use common\models\usertalent\UserTalent;
use common\services\TalentMasterService;
use common\services\InstrumentDefinition;
use common\services\MusicGenreDefinition;
use common\services\SquarePaymentService;
use common\models\useraddress\UserAddress;
use common\services\TalentMasterDefinition;
use common\models\usersquareinfo\UsersSquareInfo;
use common\services\InstrumentSpecificationService;
use common\models\usersubscription\UserSubscription;
use common\services\InstrumentSpecificationDefinition;

class ProfileController extends Controller
{
    
    private $service;

    public function __construct($id, $module, SignupService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }
    
    /**
     * @SWG\Get(
     *     path="/user/profile",
     *     tags={"Profile"},
     *     description="Returns profile info",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/Profile")
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     */
    public function actionIndex()
    {
        return $this->serializeUser($this->findModel());
    }


    /*   path="/user/profile/industry"
     *   security={{"Bearer": {}, "OAuth2": {}}}
     *   Parameter : No 
    */
    public function actionIndustry(){
        $industry_array = IndustryService::getAll();
        $industryDefination = array();
        for($i=0;$i<count($industry_array);$i++){
            $industry = $industry_array[$i];
            $industryDefination[$i] = IndustryDefinition::setDefination($industry);
        }
        return ['industry' => $industryDefination];
    }


    /*
     * path="/user/profile/talent"
     * security={{"Bearer": {}, "OAuth2": {}}}
     * Parameter : Id = Industry ID 
    */

    public function actionTalent($id){
        $talent_master_array = TalentMasterService::getTalentMasterRecordByIndustryId($id);
        $talentMasterDefination = array();
        for($i=0;$i<count($talent_master_array);$i++){
            $talent = $talent_master_array[$i]->talentMaster;
            $talentMasterDefination[$i] = TalentMasterDefinition::setDefination($talent);
        }
        return ['talent' => $talentMasterDefination];
    }


    /*
     * path="/user/profile/djgenre"
     * security={{"Bearer": {}, "OAuth2": {}}}
     * Parameter : No 
    */
    public function actionDjgenre(){
        $dj_genre_array = DjGenreService::getAll();
        $djGenreDefination = array();
        for($i=0;$i<count($dj_genre_array);$i++){
            $djGenre = $dj_genre_array[$i];
            $djGenreDefination[$i] = DjGenreDefinition::setDefination($djGenre);
        }
        return ['dj_genre' => $djGenreDefination];
    }


    /*
     * path="/user/profile/musicgenre"
     * security={{"Bearer": {}, "OAuth2": {}}}
     * Parameter : No 
     */
    public function actionMusicgenre(){
        $music_genre_array = MusicGenreService::getAll();
        $musicGenreDefination = array();
        for($i=0;$i<count($music_genre_array);$i++){
            $musicGenre = $music_genre_array[$i];
            $musicGenreDefination[$i] = MusicGenreDefinition::setDefination($musicGenre);
        }
        return ['music_genre' => $musicGenreDefination];
    }


    /*
     * path="/user/profile/instrument"
     * security={{"Bearer": {}, "OAuth2": {}}}
     * Parameter : No 
     */
    public function actionInstrument(){
        $instrument_array = InstrumentService::getAll();
        $instrumentDefination = array();
        for($i=0;$i<count($instrument_array);$i++){
            $instrument = $instrument_array[$i];
            $instrumentDefination[$i] = InstrumentDefinition::setDefination($instrument);
        }
        return ['instrument' => $instrumentDefination];
    }


    /*
     * path="/user/profile/instrumentspecification"
     * security={{"Bearer": {}, "OAuth2": {}}}
     * Parameter : Id = Instrument ID 
     */

    public function actionInstrumentspecification($id){
        $instrument_specification_array = InstrumentSpecificationService::getInstrumentSpecificationRecordByInstrumentId($id);
        $instrumentSpecificationDefination = array();
        for($i=0;$i<count($instrument_specification_array);$i++){
            $instrumentSpecification = $instrument_specification_array[$i];
            $instrumentSpecificationDefination[$i] = InstrumentSpecificationDefinition::setDefination($instrumentSpecification);
        }
        return ['instrument_specification' => $instrumentSpecificationDefination];
    }

    public function verbs(): array
    {
        return [
            'index' => ['get'],
            'industry' => ['get'],
            'talent' => ['get'],
            'djgenre' => ['get'],
            'musicgenre' => ['get'],
            'instrument' => ['get'],
            'instrumentspecification' => ['get'],
            'signup' => ['post'],
            'profile' => ['post','put'],
        ];
    }

    public function actionGetCountries(){
        $AllCountries = UserAddressService::getCountries();
        $CountriesArr = [];
        foreach($AllCountries as $country){
            $countryArr = [
                'id' => $country->id,
                'country_name' => $country->title,
            ];

            array_push($CountriesArr,$countryArr);
        }
        return $CountriesArr;
    }

    public function actionAddAddress() {

        $model = new UserAddress;
        if (Yii::$app->request->post()) {
            $result = UserAddressService::userAddress('post', \Yii::$app->user->id, null, 
                                                        $model, Yii::$app->request->post());
            if ($result) {
                return [
                    'status' => '201',
                    'message' => 'Address Added successfully',
                    'customerAddressCreate'=> 
                                ['customerAddress' => $result ],
                ];
            }
            return [ 
                'status' => '400',
                'data'=>[
                    'customerAddressCreate'=>[
                            'Address'=> null,
                            'AddressErrors'=>$model->getErrors()
                     ],
                    
                    ],
                'message' => 'Invalid Data',
            ];
        }
        return [ 
            'status' => '400',
            'data'=>[
                'customerAddressCreate'=>[
                        'Address'=> null,
                        'AddressErrors'=> null
                 ],
                
                ],
            'message' => 'No Data Found',
        ];
    }

    public function actionGetAddress($uid=''){
        $userAddressArr = [];
        $uid = ($uid!='')?$uid:\Yii::$app->user->id;
        $userAddress = UserAddressService::userAddress('get', $uid);
        foreach($userAddress as $address){
            $addressArr = [
                'id' => $address->id,
                'country' => [
                    'id' => $address->country->id,
                    'country_name' => $address->country->title
                ],
                'first_name' => $address->first_name,
                'last_name' => $address->last_name,
                'phone_number' => $address->phone_number,
                'state' => $address->state,
                'city' => $address->city,
                'area' => $address->area,
                'postal_code' => $address->postal_code,
                'address' => $address->address,
                'default' => $address->default
            ];
            array_push($userAddressArr,$addressArr);
        }
        return $userAddressArr;
    }

    public function actionDeleteAddress($id){
        $model = UserAddressService::userAddress('get', \Yii::$app->user->id, $id);
        if($model == NULL || $model->is_deleted == 1){
            return [ 
                'status' => '400',
                'data'=>[
                    'profileUpdate'=>[
                            'Address'=> null,
                            'AddressErrors'=> null
                     ],
                    
                    ],
                'message' => 'User Address Not Found',
            ];
        }
        
        $result = UserAddressService::userAddress('delete', null, $id);

        if ($result) {
            return [
                'status' => '201',
                'message' => 'Address Deleted successfully',
            ];
        }
        return [ 
            'status' => '400',
            'data'=>[
                'profileUpdate'=>[
                        'Address'=> null,
                        'AddressErrors'=> 'User Address Not Found'
                 ],
                
                ],
            'message' => 'Invalid Data',
        ];

    }

    public function actionUpdateAddress($id){
        $model = UserAddressService::userAddress('get', \Yii::$app->user->id, $id);
        if (Yii::$app->request->post()) {

            if($model == NULL || $model->is_deleted == 1){
                return [ 
                    'status' => '400',
                    'data'=>[
                        'customerAddressUpdate'=>[
                                'Address'=> null,
                                'AddressErrors'=> null
                         ],
                        
                        ],
                    'message' => 'User Address Not Found',
                ];
            }



            $result = UserAddressService::userAddress('put', \Yii::$app->user->id, null, $model, Yii::$app->request->post());
            if ($result) {
                return [
                    'status' => '201',
                    'message' => 'Address Updated successfully',
                    'customerAddressUpdate'=> 
                                ['customerAddress' => $result ,
                                'customer' => $this->serializeUser($this->findModel()) ],
                               
                ];
            }
            return [ 
                'status' => '400',
                'data'=>[
                    'customerAddressUpdate'=>[
                            'Address'=> null,
                            'AddressErrors'=>$model->getErrors()
                     ],
                    
                    ],
                'message' => 'Invalid Data',
            ];
        }
        return [ 
            'status' => '400',
            'data'=>[
                'customerAddressUpdate'=>[
                        'Address'=> null,
                        'AddressErrors'=> null
                 ],
                
                ],
            'message' => 'No Data Found',
        ];
    }


    public function actionProfile() {
        $userTalent = UserTalent::find()->where(['user_id' => \Yii::$app->user->id])->one();

        if ($userTalent == NULL) {
            $userTalent = new UserTalent;
        }

        if (Yii::$app->request->post()) {
            $dataObj = Yii::$app->request->post();
            $userTalent->attributes = $dataObj;
            $userTalent->user_id = \Yii::$app->user->id;
            $userTalent->created_at = time();
            $userTalent->created_by = \Yii::$app->user->id;
            $userTalent->modified_at = time();
            $userTalent->modified_by = \Yii::$app->user->id;

            if ($userTalent->save()) {
                return [
                    'status' => '201',
                    'message' => 'Profile successfully Updated',
                ];
            }
            return [ 
                'status' => '400',
                'data'=>[
                    'profileUpdate'=>[
                            'profile'=> null,
                            'profileErrors'=>$userTalent->getErrors()
                     ],
                    
                    ],
                'message' => 'Invalid Data',
            ];
        }

        return [ 
            'status' => '400',
            'data'=>[
                'profileUpdate'=>[
                        'profile'=> null,
                        'profileErrors'=> null
                 ],
                
                ],
            'message' => 'No Data Found',
        ];
    }

    public function actionPlan(){
        $plansArr = [];
        $plans = Membership::find()->where(['status' => 'active', 'is_deleted' => 0])
                ->orderBy('sort ASC')
                ->all();
        $i=0;
        foreach($plans as $plan){
           $plansArr[$i] = DataHelper::serializeMemberShipPlan($plan);
           $i++;
        }
        if($plansArr != null) {
            return [ 
                'status' => '200',
                'data'=>[
                    'plans'=> $plansArr,
                    ],
                'message' => 'Data Found',
            ];
        }
        return [ 
            'status' => '400',
            'data'=>[
                'plans'=> $plansArr,
                ],
            'message' => 'Data Not Found',
        ]; 
    }

    public function actionAddons() {

        $userSubscription = UserSubscription::find()->where(['user_id' => \Yii::$app->user->id, 'type' => 'membership', 'status' => 'active'])->one();
        $addonsArr = [];
        if($userSubscription != null) { 

            if($userSubscription->ref_id == 6){
                $plans = Membership::find()->where(['status' => 'active', 'is_deleted' => 0, 'id' => '1'])
                    ->orderBy('sort ASC')
                    ->one();

                $addonsArr[0] =  DataHelper::serializeMemberShipPlan($plans);
                return [
                    'status' => '200',
                    'data'=>[
                        'upgrade_membership' =>  $addonsArr[0],
                    ],
                    'message' => 'Upgrade membership founds',
                ];
            }      

            if($userSubscription->ref_id == 7){
                $plans = Membership::find()->where(['status' => 'active', 'is_deleted' => 0, 'id' => '2'])
                    ->orderBy('sort ASC')
                    ->one();

                $addonsArr[0] =  DataHelper::serializeMemberShipPlan($plans);
                return [
                    'status' => '200',
                    'data'=>[
                        'upgrade_membership' =>  $addonsArr[0],
                    ],
                    'message' => 'Upgrade membership founds',
                ];
            }

            if($userSubscription->ref_id == 1 || $userSubscription->ref_id == 2 || $userSubscription->ref_id == 3) {
                $items = MsItems::find()->where(['membership_id' => $userSubscription->ref_id, 'type' => 'addons'])->all();
                $addonsArr = DataHelper::serializeMSItems($items);
                return [
                    'status' => '200',
                    'data'=>[
                        'addons' => $addonsArr,
                        ],
                    'message' => 'Addons founds',
                ];
            }
            if($userSubscription->ref_id == 4){
                return [ 
                    'status' => '400',
                    'data'=>[
                        'addons'=> $addonsArr,
                        ],
                    'message' => 'Fan has no subscription',
                ];
            }
            if($userSubscription->ref_id == 5){
                return [ 
                    'status' => '400',
                    'data'=>[
                        'addons'=> $addonsArr,
                        ],
                    'message' => 'Custumer has no subscription',
                ];
            }
        }
        return [ 
            'status' => '400',
            'data'=>[
                'addons'=> $addonsArr,
                ],
            'message' => 'User Not Subscribe Any MemberShip',
        ]; 
    }

    public function actionGetCards() {
        $cards = UsersSquareInfo::find()->where(['user_id' => \Yii::$app->user->id])->all();
        if($cards != null)
        {
            if(\Yii::$app->user->identity->getUser()->square_cust_id != null){
                $square = new SquarePaymentService;
                $result = $square->retrieveCustomer(\Yii::$app->user->identity->getUser()->square_cust_id);
                if(!is_object($result))
                {
                    return [
                        'code' => 400,
                        'message' => $result,
                        ];
                }
                $errors = $result->getErrors();

                if($errors != null){
                    $errorArr = [];
                    $i=0;
                    foreach($errors as $error)
                    {
                        $errorArr[$i] = $error->getDetails();
                        $i++;
                    }  
                    return [
                        'code' => 400,
                        'message' => $errorArr,
                    ];
                }
                $customer = $result->getCustomer();
                $cards = $customer->getCards();
                $cardsArr = [];
                $customerArr = [
                    'id' => $customer->getId(),
                    'name' => $customer->getGivenName(),
                    'email' => $customer->getEmailAddress(),
                    'refId' => $customer->getReferenceId(),
                    
                ];
                $i=0;
                foreach($cards as $card){
                    $cardsArr[$i] = [
                        'sourceId' => $card->getId(),
                        'cardBrand' => $card->getCardBrand(),
                        'last4Digit' => $card->getLast4(),
                        'expMonth' => $card->getExpMonth(),
                        'expYear' => $card->getExpYear(),
                    ];
                    $i++;
                }

                return [
                        'code' => 200,
                        'message' => 'Card Found',
                        'data' => [
                            'customerDetail' => $customerArr,
                            'customerCards' => $cardsArr
                        ],
                    ];
            }
            return [
                'code' => 400,
                'message' => 'Square Id Not Found',
                ];
        }
        return [
            'code' => 400,
            'message' => 'No Card Found',
        ];
    }

    public function actionSignup(){ 
        

        $form = new SignupForm;

        if(Yii::$app->request->post()){
            $form = $this->service->setForm(Yii::$app->request->post(), 'api');
            if ($form->validate()) {
                try {
                    $this->service->signup($form);
                    $form = $this->service->unsetFormPwd($form);
                    return [ 
                        'status' => '201',
                        'data'=>[
                            'customerCreate'=>[
                                    'customer'=> $form,
                                    'userErrors'=>$form->getErrors()
                             ],
                            ],
                        'message' => 'You have successfully signed-up',
                         ];
                } catch (\DomainException $e) {
                    return [ 
                        'status' => '400',
                        'data'=>[
                            'customerCreate'=>[
                                    'customer'=> null,
                                    'userErrors'=>$form->getErrors()
                             ],
                            
                            ],
                        'message' => 'Invalid Data',
                         ];
                               
                }
            }
        }
        return [ 
            'status' => '400',
            'data'=>[
                'customerCreate'=>[
                        'customer'=> null,
                        'userErrors'=>$form->getErrors()
                 ],
                
                ],
            'message' => 'Invalid Data',
             ];

    }

    private function findModel(): User
    {
        return User::findOne(\Yii::$app->user->id);
    }

    private function getDefaultAddress($addresses){
        foreach($addresses as $address){
            if($address['default']===1){
                return $address;
            }
        }
        return null;
    }

    

    private function serializeUser(User $user): array
    {
        
        $addresses = $this->actionGetAddress($user->id);
        $defaultAddress = $this->getDefaultAddress($addresses);
        $subscriptions = $user->getSubscription('membership');
        
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'country' => $user->country,
            'date' => [
                'created' => DateHelper::formatApi($user->created_at),
                'updated' => DateHelper::formatApi($user->updated_at),
            ],
            'status' => [
                'code' => $user->status,
                'name' => UserHelper::statusName($user->status),
            ],
            'membership' =>  DataHelper::serializeMemberShips($subscriptions),
            'addresses'=> $addresses,
            'defaultAddress' => $defaultAddress,
            'talent' => [
                "id" => $user->userTalent->id,
                "industry"=> $user->userTalent->industry,
                "talent_master" => $user->userTalent->talent,
                "gender" => $user->userTalent->gender,
                "dj_genre" => $user->userTalent->djgenre,
                "instrument" => $user->userTalent->instrument,
                "instrument_spec" => $user->userTalent->instrumentspecification,
                "music_genre" => $user->userTalent->musicgenre,
            ],
            'update_talent_profile' => $user->canUpdateProfile(),
        ];
    }
}

/**
 *  @SWG\Definition(
 *     definition="Profile",
 *     type="object",
 *     required={"id"},
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="name", type="string"),
 *     @SWG\Property(property="email", type="string"),
 *     @SWG\Property(property="city", type="string"),
 *     @SWG\Property(property="role", type="string")
 * )
 */