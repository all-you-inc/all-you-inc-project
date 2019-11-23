<?php

namespace api\controllers\user;

use Yii;
use yii\rest\Controller;
use api\helpers\DataHelper;
use shop\entities\User\User;
use shop\forms\auth\SignupForm;
use common\services\DjGenreService;
use common\services\IndustryService;
use common\models\membership\MsItems;
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
use common\services\UserMlmService;
use common\models\userprofileimage\UserProfileImage;
use shop\forms\manage\Shop\Product\PhotosForm;
use yii\helpers\BaseFileHelper;
use yii\data\Pagination;
use common\services\GalleryService;

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
        return DataHelper::serializeUser($this->findModel());
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

    public function actionGetMyTeam(){
        $user = Yii::$app->user;
        $referrals = UserMlmService::getUserReferralByReferralId($user->id);
        $teamCount = count($referrals);
        $tree = [];
        
        if ($teamCount == 0) {
            return [
                'status' => 404,
                'message' => 'User referrals not found.'
            ];
        }
        
        foreach ($referrals as $referral) {
            $tree[$referral->referralCodeUser->id == $user->id ? 'root' : $referral->referralCodeUser->id][$referral->user->id] = DataHelper::serializeUserShort($referral->user);
        }
        
        return [
            'status' => 200,
            'data' => [
                'team_count' => $teamCount,
                'tree' => $tree
            ]
        ];
    }

    /*
     * path="/user/profile/talent"
     * security={{"Bearer": {}, "OAuth2": {}}}
     * Parameter : Id = Industry ID 
    */

    public function actionTalent($id){
        $talent_master_array = TalentMasterService::getTalentMasterRecordByIndustryId($id);
        $talentMasterDefinition = array();
        for($i=0;$i<count($talent_master_array);$i++){
            $talent = $talent_master_array[$i]->talentMaster;
            $talentMasterDefinition[$i] = TalentMasterDefinition::setDefination($talent);
        }
        return ['talent' => $talentMasterDefinition];
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
            // 'index' => ['*'],
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

    public function actionGetUserSubscribedItems()
    {
        $user = \Yii::$app->user->identity->getUser();
        $items = $user->getSubscribedItems();
        if($items != NULL){
            return [
                'status' => '200',
                'data' => [
                    'items' => DataHelper::serializeMSItems($items),
                ],
                'message' => 'Get all subscribed items Successfully',
            ];
        }
        return [
            'status' => '400',
            'data' => [],
            'message' => 'User Not Subscribe Any MemberShip'
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

    public function actionUploadGallery()
    {
        $model = new PhotosForm;
        if (\Yii::$app->request->post()) {        
            $type = \Yii::$app->request->post()['type'];
            if($type == null || ($type != 'image-gallery' && $type != 'video-gallery')){
                return [
                    'status' => '400',
                    'data'=>[
                        'userImageUpload'=>[
                                'userProfile'=> null,
                                'userProfileErrors'=> null
                         ],
                        ],
                    'message' => 'Image Type Not Found Or InCorrect',
                ];
            }

            if ($model->validate()) {
                   $upload = GalleryService::upload($model, $type);
                   if($upload['status'] == 200){
                        return [ 
                            'status' => $upload['status'],
                            'message' => $upload['message'],
                        ];
                   }
                        return [ 
                            'status' => $upload['status'],
                            'message' => $upload['message'],
                        ];
            }
        }
    }
    
    public function actionGetPublicGallery($type = null ,$user = null ) {

       
        $gallery = GalleryService::gallery('get',$user,$type);
        if ($gallery) {
            if($gallery['dataProvider']){
                foreach($gallery['dataProvider'] as $item){
                    if($item->show_on == 'image-gallery')
                        $item->image_name = UserProfileImage::getGalleryImagePath($item);                            

                    elseif($item->show_on == 'video-gallery')
                        $item->image_name = UserProfileImage::getGalleryVideoPath($item);              
              }
        }
                return [
                    'status' => '201',
                    'message' => 'Successfully found record',
                    'data'=>$gallery
                ];
            }
            return [ 
                'status' => '404',
                'data'=>[],
                'message' => 'Record not found'
            ];
    }

    public function actionGetGallery($type = null ,$user = null ) {

        if($user==null){
            $user =  \Yii::$app->user->id;
        }        

        $gallery = GalleryService::gallery('get',$user,$type);
        if ($gallery) {
            if($gallery['dataProvider']){
                foreach($gallery['dataProvider'] as $item){
                    if($item->show_on == 'image-gallery')
                        $item->image_name = UserProfileImage::getGalleryImagePath($item);                            

                    elseif($item->show_on == 'video-gallery')
                        $item->image_name = UserProfileImage::getGalleryVideoPath($item);              
              }
        }
                return [
                    'status' => '201',
                    'message' => 'Successfully found record',
                    'data'=>$gallery
                ];
            }
            return [ 
                'status' => '404',
                'data'=>[],
                'message' => 'Record not found'
            ];
    }
    
    public function actionDeleteGallery($id) {
        if ($id) {
            $model = GalleryService::gallery('delete', null, null, null, $id);
            if ($model) {
                 return [
                    'status' => '201',
                    'message' => 'Successfully deleted',
                ];
            }
        }
           return [
                    'status' => '404',
                    'message' => 'Record not found'
                ];
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
                                ['customerAddress' => DataHelper::serializeAddress($result) ],
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
            // $addressArr = [
            //     'id' => $address->id,
            //     'country' => [
            //         'id' => $address->country->id,
            //         'country_name' => $address->country->title,
            //         'iso' =>$address->country->iso_code_2,
                    
            //     ],
            //     'first_name' => $address->first_name,
            //     'last_name' => $address->last_name,
            //     'phone_number' => $address->phone_number,
            //     'state' => $address->state,
            //     'city' => $address->city,
            //     'area' => $address->area,
            //     'postal_code' => $address->postal_code,
            //     'address' => $address->address,
            //     'default' => $address->default
            // ];
            $addressArr = DataHelper::serializeAddress($address);    


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
                                ['customerAddress' => DataHelper::serializeAddress($result) ,
                                'customer' => DataHelper::serializeUser($this->findModel()) ],
                               
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

    public function actionBasicInfo() {

        $user = User::find()->where(['id' => \Yii::$app->user->id])->one();

        if (Yii::$app->request->post()) {
            $dataObj = Yii::$app->request->post();
            
            $countryId = 0;
            if(isset($dataObj['User']['country'])){
                $country = UserAddressService::getCountry4mIso($dataObj['User']['country']);
                $countryId = $country->id;
            }        
            $user->attributes = $dataObj;
            $user->name = $dataObj['User']['name'];
            $user->phone = $dataObj['User']['phone'];
            $user->city = $dataObj['User']['city'];
            $user->state = $dataObj['User']['state'];
            $user->country = $countryId;

            if ($user->save()) {
                return [
                    'status' => '201',
                    'user' => Yii::$app->runAction('user/profile/index',[]),
                    'message' => 'Profile successfully Updated',
                ];
            }
            return [ 
                'status' => '400',
                'data'=>[
                    'profileUpdate'=>[
                            'profile'=> null,
                            'profileErrors'=>$user->getErrors()
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

    public function actionProfile() {
        $userTalent = UserTalent::find()->where(['user_id' => \Yii::$app->user->id])->one();

        if ($userTalent == NULL) {
            $userTalent = new UserTalent;
        }

        if (Yii::$app->request->post()) {
            $dataObj = Yii::$app->request->post();
            if(isset($dataObj['industry_id']) && $dataObj['industry_id'] != null){
                $userTalent->industry_id = $dataObj['industry_id'];
            }else{
                $userTalent->industry_id = null;
            }
            if(isset($dataObj['talent_id']) && $dataObj['talent_id'] != null){
                $userTalent->talent_id = $dataObj['talent_id'];
            }else{
                $userTalent->talent_id = null;
            }
            if(isset($dataObj['gender']) && $dataObj['gender'] != null){
                $userTalent->gender = $dataObj['gender'];
            }else{
                $userTalent->gender = null;
            }
            if(isset($dataObj['dj_genre_id']) && $dataObj['dj_genre_id'] != null){
                $userTalent->dj_genre_id = $dataObj['dj_genre_id'];
            }else{
                $userTalent->dj_genre_id = null;
            }
            if(isset($dataObj['instrument_id']) && $dataObj['instrument_id'] != null){
                $userTalent->instrument_id = $dataObj['instrument_id'];
            }else{
                $userTalent->instrument_id = null;
            }
            if(isset($dataObj['instrument_spec_id']) && $dataObj['instrument_spec_id'] != null){
                $userTalent->instrument_spec_id = $dataObj['instrument_spec_id'];
            }else{
                $userTalent->instrument_spec_id = null;
            }
            if(isset($dataObj['music_genre_id']) && $dataObj['music_genre_id'] != null){
                $userTalent->music_genre_id = $dataObj['music_genre_id'];
            }else{
                $userTalent->music_genre_id = null;
            }
            $userTalent->user_id = \Yii::$app->user->id;
            $userTalent->created_at = time();
            $userTalent->created_by = \Yii::$app->user->id;
            $userTalent->modified_at = time();
            $userTalent->modified_by = \Yii::$app->user->id;

            if ($userTalent->save()) {
                return [
                    'status' => '201',
                    'message' => 'Profile successfully Updated',
                    'user' => Yii::$app->runAction('user/profile/index',[]),
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
        $plans = Membership::find()->where(['status' => 'active', 
                                            'is_deleted' => 0,
                                            'is_main' => 1])
                ->orderBy('sort ASC')
                ->all();
        $i=0;
        foreach($plans as $plan){
           $plansArr[$i] = DataHelper::serializeMemberShipPlan($plan,true);
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

    public function actionGetTalents()
    {
        $talentArr = [];
        $talents = UserTalent::find()->where(['user_id' => Yii::$app->user->id])->all();
        $i=0;
        foreach($talents as $talent)
        {
            $talentArr[$i] = DataHelper::serializeTalents($talent);
            $i++;
        }
        return $talentArr;
    }

    public function actionGetFunds()
    {
        $fundsArr = [];
        $limit = null;
        if(isset(\Yii::$app->request->get()['limit']) && \Yii::$app->request->get()['limit'] !== null){
            $limit = \Yii::$app->request->get()['limit'];
        }

        $userFunds = UserMlmService::getUserFunds(Yii::$app->user->id,$limit);
        $i=0;
        $totalPrice = 0;
        $fundsArr['data'] = [];
        foreach($userFunds['results'] as $fund)
        {
            $fundsArr['data'][$i] = DataHelper::serializeFunds($fund);
            $totalPrice += $fund['amount'];
            $i++;
        }

        $fundsArr['totalPrice'] = $totalPrice;
        $fundsArr['grandTotalPrice'] = $userFunds['total'];
        $fundsArr['totalItems'] = $userFunds['pages']->totalCount;
        $fundsArr['currentPages'] = $userFunds['pages']->getPage()+1;
        $fundsArr['totalPages'] = $userFunds['pages']->getPageCount();
        return $fundsArr;
    }

    public function actionGetAllTalentUsers(){
        $dataObj = \Yii::$app->request->get();
        $searchName = isset($dataObj['query']) ? $dataObj['query'] : null;
        $limit = isset($dataObj['limit']) ? $dataObj['limit'] : 10;
        $talentsArr = [];
        $condition = '';

        $dataProvider = UserTalent::find();
        if ($searchName != null) {
            $dataProvider = $dataProvider->leftJoin('industry', 'industry.`id` = user_talent.`industry_id`');
            $dataProvider = $dataProvider->leftJoin('talent_master', 'talent_master.`id` = user_talent.`talent_id`');
            $condition .= 'industry.name LIKE (\'%' . $searchName . '%\') OR talent_master.name LIKE (\'%' . $searchName . '%\')';
            $dataProvider = $dataProvider->where($condition);
        }
        $countQuery = clone $dataProvider;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pages->defaultPageSize = $limit;
        $dataProvider = $dataProvider->offset($pages->offset)->limit($pages->limit)->all();
        $i=0;
        foreach($dataProvider as $talent){
            $talentsArr['data'][$i] = DataHelper::serializeTalents($talent);
            $i++;
        }
        $talentsArr['totalItems'] = $pages->totalCount;
        $talentsArr['currentPages'] = $pages->getPage()+1;
        $talentsArr['totalPages'] = $pages->getPageCount();
        return $talentsArr;
    }

    public function actionImage()
    {
        // dd(\Yii::$app->request->post());
        $model = new PhotosForm;
        if (\Yii::$app->request->post()) {
            
            $type = \Yii::$app->request->post()['type'];
            if($type == null || ($type != 'profile' && $type != 'banner')){
                return [ 
                    'status' => '400',
                    'data'=>[
                        'userImageUpload'=>[
                                'userProfile'=> null,
                                'userProfileErrors'=> null
                         ],
                        
                        ],
                    'message' => 'Image Type Not Found Or InCorrect',
                ];
            }
               
            if ($model->validate()) {
                  
                if($type == 'profile'){
                    $path = UserProfileImage::getfullPath(UserProfileImage::$show_on_profile);
                }else{
                    $path = UserProfileImage::getfullPath(UserProfileImage::$show_on_banner);
                }
                $name = \Yii::$app->security->generateRandomString();
                if (is_dir($path)) {
                    UserProfileImage::deletePreviousFile($path);
                }
                if (!is_dir($path)) {
                    BaseFileHelper::createDirectory($path);
                }
                
                if ($model->files[0]->saveAs($path . $name . '.' . $model->files[0]->extension) !== false) {
                    if($type == 'profile'){
                        $image = UserProfileImage::createImage($name, $model->files[0]->extension, UserProfileImage::$show_on_profile);
                    }else{
                        $image = UserProfileImage::createImage($name, $model->files[0]->extension, UserProfileImage::$show_on_banner);
                    }
                    if ($image->save()) {
                        return [ 
                            'status' => '200',
                            'data'=>[
                                'userImageUpload'=>[
                                        'userProfile'=> null,
                                        'userProfileErrors'=> null,
                                        'profileImage' => UserProfileImage::getProfileImage(),
                                        'bannerImage' => UserProfileImage::getBannerImage(),
                                 ],
                                
                                ],
                            'user' => Yii::$app->runAction('user/profile/index',[]),  
                            'message' => 'Image Successfully Uploaded',
                        ];
                    }
                    return [ 
                        'status' => '400',
                        'data'=>[
                            'userImageUpload'=>[
                                    'userProfile'=> null,
                                    'userProfileErrors'=> $image->getErrors(),
                             ],
                            
                            ],
                        'message' => 'Invalid Data',
                    ];
                }
                return [ 
                    'status' => '400',
                    'data'=>[
                        'userImageUpload'=>[
                                'userProfile'=> null,
                                'userProfileErrors'=> $model->files[0]->error,
                         ],
                        
                        ],
                    'message' => 'Invalid Data',
                ];
            }
            return [ 
                'status' => '400',
                'data'=>[
                    'userImageUpload'=>[
                            'userProfile'=> null,
                            'userProfileErrors'=> $model->getErrors(),
                     ],
                    
                    ],
                'message' => 'Invalid Data',
            ];
        }
        return [ 
            'status' => '400',
            'data'=>[
                'userImageUpload'=>[
                        'userProfile'=> null,
                        'userProfileErrors'=> null
                 ],
                
                ],
            'message' => 'Request Must Be Post With Image',
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
     
                    if (isset(Yii::$app->request->post()['referral']) && Yii::$app->request->post()['referral'] != NULL) {
                        UserReferralService::createReferrals(Yii::$app->request->post()['referral'], $form->email);
                    }
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