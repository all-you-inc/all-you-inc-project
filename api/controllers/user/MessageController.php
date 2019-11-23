<?php

namespace api\controllers\user;

use Yii;
use yii\helpers\Url;
use yii\rest\Controller;
use api\helpers\DataHelper;
use api\helpers\DateHelper;
use common\models\chat\Message;
use common\models\chat\Thread;
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
use common\services\UserMlmService;
use common\models\userprofileimage\UserProfileImage;
use common\services\ChatService;
use shop\forms\manage\Shop\Product\PhotosForm;
use yii\helpers\BaseFileHelper;
use yii\data\Pagination;

class MessageController extends Controller {

    private $service;

    public function __construct($id, $module, ChatService $service, $config = []) {
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
    public function actionThreads() {
        $data = $this->service->getAllThread(\Yii::$app->user->id);
        if(isset($data['data'] )){
                foreach ($data['data'] as &$item) {
                    $item = DataHelper::serliazeMessageThread($item);
                }
        }
        return $data;

    }

    public function actionThreadMessages() {
        $thread = \Yii::$app->getRequest()->get("thread");
        
        $data = $this->service->getThreadMessages($thread,'',1000);
        foreach ($data['data'] as &$item) {
            $item = DataHelper::serliazeMessage($item);
        }
        $this->service->updateThreadUnreadCount($thread, \Yii::$app->user->id);
        return $data;
    }

    public function actionCreate() {
        $payload = \Yii::$app->getRequest()->post();

        $data = $this->service->createMessage($payload);
        if ($data instanceof Message) {
            return ["status" => 200, "data" => $data];
        }
        return ["status" => 400, "errors" => $data];
    }

    public function actionCreateThread() {
        $payload = \Yii::$app->getRequest()->post();

        $data = $this->service->createThread($payload);
        if ($data instanceof Thread) {
            return ["status" => 200, "data" => $data];
        }
        return ["status" => 400, "errors" => $data];
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