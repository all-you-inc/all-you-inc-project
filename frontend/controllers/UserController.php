<?php

namespace frontend\controllers;

use DateTime;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use shop\entities\Shop\Product\Product;
use common\models\usertalent\UserTalent;
use common\services\UserAddressService;
use shop\entities\User\User;
use common\models\useraddress\UserAddress;
use shop\forms\manage\Shop\Product\PhotosForm;
use common\models\userprofileimage\UserProfileImage;
use yii\helpers\BaseFileHelper;
use common\services\IndustryService;
use common\services\UserAccessService;
use common\services\UserPaymentService;
use common\services\UserDashboardService;
use common\models\membership\MsItems;
use common\models\usersubscription\UserSubscription;
use common\models\membership\Membership;
use common\models\userfunds\UserFunds;
use common\services\UserMlmService;
use common\services\SquarePaymentService;
use yii\data\Pagination;
use common\services\GalleryService;
use shop\entities\Shop\Order\Order;
use common\services\NotificationService;

class UserController extends Controller {

    public function actionDashboard() {
        $user = \Yii::$app->user->identity->getUser();
        $request = Yii::$app->request->get();
        $talentId = isset($request['talentId']) ? $request['talentId'] : NULL;
        $productId = isset($request['productId']) ? $request['productId'] : NULL;

        $conditions = "created_by = {$user->id}";
        if ($talentId) { $conditions .= " AND talent_id = $talentId"; }
        $products = Product::find()->where($conditions)->orderBy(['name' => SORT_ASC])->all();
        
        $current = new DateTime();
        $start = (new DateTime())->setDate($current->format('Y'), 1, 1)->setTime(0,0,0);
        $end = (new DateTime())->setDate($current->format('Y'), 12, 31)->setTime(23,59,59);
        
        $totalSales = UserDashboardService::getTotalSales($start, $end, $products ? FALSE : TRUE, $talentId, $productId);
        $activeChart = UserDashboardService::getActiveChart($start, $end, $products ? FALSE : TRUE, $talentId, $productId);
        $activeMap = UserDashboardService::getActiveMap($start, $end, $products ? FALSE : TRUE, $talentId, $productId);

        return $this->render('dashboard', [
            'talentId' => $talentId,
            'productId' => $productId,
            'products' => $products,
            'totalOrders' => $user->getTotalOrders(),
            'totalSales' => $totalSales,
            'activeChart' => $activeChart,
            'activeMap' => $activeMap
        ]);
    }

    public function actionPortfolio($id) {
        $talent = UserTalent::findOne($id);
        $videos = GalleryService::gallery('get', $talent->user_id, UserProfileImage::$show_on_video_gallery, 8, null, true);
        $photos = GalleryService::gallery('get', $talent->user_id, UserProfileImage::$show_on_image_gallery, 8, null, true);
        return $this->render('portfolio', ['videos' => $videos['dataProvider'], 'photos' => $photos['dataProvider'], 'talent' => $talent, 'id' => $talent->user_id]);
    }

    public function actionTree() {
        $referral = UserMlmService::getReferralCountAndLevel(\Yii::$app->user->identity->getUser());
        return $this->render('tree', ['tree' => $referral['tree']]);
    }

    public function actionReferralEarn($referral) {
        $this->layout = 'main';
        $userFunds = UserMlmService::getUserFunds(Yii::$app->user->id,10,$referral);
        return $this->render('earn', [
                    'funds' => $userFunds['results'],
                    'pages' => $userFunds['pages'],
                    'total' => $userFunds['total'],
        ]);
    }

    public function actionUpdatenoticount() {
        if (NotificationService::updateUserNotificationSeens()) {
            echo 1;
        }
            echo 0;
    }

    public function actionNotificationsetting() {
        $events = NotificationService::getAllNotificationEvents();
        if ($_POST) {
            NotificationService::postUserNotificationEvents($_POST['events'], \Yii::$app->user->id);
            Yii::$app->session->setFlash('success', 'Successfully set notification settings');
        }
        $user_events = NotificationService::getAllUserNotificationEvents(\Yii::$app->user->id);
        return $this->render('notification', ['user_events' => $user_events, 'events' => $events]);
    }

    public function actionWallet() {
        $this->layout = 'main';
        $userFunds = UserMlmService::getUserFunds(Yii::$app->user->id);
        return $this->render('wallet', [
                    'funds' => $userFunds['results'],
                    'pages' => $userFunds['pages'],
                    'total' => $userFunds['total'],
        ]);
    }

    public function actionDeletegallery($id) {
        if ($id) {
            $model = GalleryService::gallery('delete', null, null, null, null, null, $id);
            if ($model) {
                Yii::$app->session->setFlash('success', 'Successfully deleted');
                return TRUE;
            }
        }
        return FALSE;
    }

    public function actionGallery() {
        $this->layout = 'main';
        $photos = GalleryService::gallery('get', \Yii::$app->user->id, UserProfileImage::$show_on_image_gallery);
        $videos = GalleryService::gallery('get', \Yii::$app->user->id, UserProfileImage::$show_on_video_gallery);
        return $this->render('gallery', [
                    'dataProvider' => $photos['dataProvider'],
                    'videos' => $videos['dataProvider'],
                    'pages' => $photos['pages'],
        ]);
    }

    public function actionSubscription() {
        $this->layout = 'main';
        $session = \Yii::$app->session;
        $params = [];
        $params['note'] = 'Purchase Addons';
        // Check If User Select Free MemberShip 
        // So Give Upgrade Plan According to Free Slected MemberShip
        $user = \Yii::$app->user->identity->getUser();
        $membership_id = $user->getMembershipId();
        if ($membership_id == Membership::FreeTalent) {
            $membership_id = Membership::Talent;
        } elseif ($membership_id == Membership::FreeTalentWithProduct) {
            $membership_id = Membership::TalentWithProduct;
        }

        // If Free MemberShip User Make Upgrade Plan In UI
        // Only For Free Membership User
        $basic = [];
        if ($user->getMembershipId() != Membership::Talent &&
                $user->getMembershipId() != Membership::TalentWithProduct) {
            $basic = UserPaymentService::getAllSubscriptions($membership_id, 'basic');
        }
//        for downgrading membership
        $free = [];
        if ($user->getMembershipId() == Membership::Talent || $user->getMembershipId() == Membership::TalentWithProduct) {
            $free_membership_id = $user->getMembershipId() == Membership::Talent ? Membership::FreeTalent : Membership::FreeTalentWithProduct;
            $free = UserPaymentService::getAllSubscriptions($free_membership_id, 'free');
        }
        // If Paid MemberShip User Make Addons According To Membership In UI
        // Only For Paid MemberShip User
        if ($user->getMembershipId() != Membership::FreeTalent && $user->getMembershipId() != Membership::FreeTalentWithProduct) {
            $addons = UserPaymentService::getAllSubscriptions($membership_id, 'addons');
        }

        // Not implemented In this MileStone
        // $membership = UserPaymentService::getAllSubscriptions($membership_id, 'membership');

        if (\Yii::$app->request->post()) {
            // Make Group-ID
            $group_id = abs(crc32(uniqid()));

            $dataObj = [
                'basic' => isset(Yii::$app->request->post()['basic']) ? $membership_id : null,
                'addons' => isset(Yii::$app->request->post()['addons']) ? Yii::$app->request->post()['addons'] : null,
                'free' => isset(Yii::$app->request->post()['free']) ? Yii::$app->request->post()['free'] : null,
            ];
            // DownGrade MemberShip
            if ($dataObj['free'] != NULL) {
                UserPaymentService::createSubscription('membership', $dataObj['free'], $user->id, $group_id);
                if ($membership_id == Membership::TalentWithProduct) {
                    Product::updateAll(['is_locked' => 1], ['created_by' => $user->id]);
                    $products = Product::find()->where(['created_by' => $user->id])->orderBy(['id' => SORT_DESC])->limit(5)->all();
                    if ($products) {
                        foreach ($products as $product) {
                            $product->is_locked = 0;
                            $product->update();
                        }
                    }
                }
                UserProfileImage::updateAll(['is_locked' => 1], ['user_id' => $user->id, 'show_on' => UserProfileImage::$show_on_image_gallery]);
                UserProfileImage::updateAll(['is_locked' => 1], ['user_id' => $user->id, 'show_on' => UserProfileImage::$show_on_video_gallery]);
                if ($membership_id == Membership::Talent) {
                    $images = UserProfileImage::find()->where(['user_id' => $user->id, 'show_on' => UserProfileImage::$show_on_image_gallery])->orderBy(['id' => SORT_DESC])->limit(5)->all();
                    if ($images) {
                        foreach ($images as $image) {
                            $image->is_locked = 0;
                            $image->update();
                        }
                    }
                }
                Yii::$app->session->setFlash('success', 'Subscription add successfully');
                return $this->redirect(['/profile']);
            }
            if (isset(\Yii::$app->request->post()['card_given']) && \Yii::$app->request->post()['card_given'] == 1) {
                SquarePaymentService::$checkoutCard = 1;
                $dataObj = $session['addons'];
            }

            if ($dataObj['basic'] != null || $dataObj['addons'] != null || $dataObj['free'] != null) {
                $totalPrice = isset($session['addonsTotalPrice']) ? $session['addonsTotalPrice'] : 0;
                if (SquarePaymentService::$checkoutCard == 0) {
                    $session['addons'] = $dataObj;
                    return $this->redirect(\yii\helpers\Url::base('https') . '/' . 'square/details?request=addons');
                }

                if ($dataObj['addons'] == null) {
                    $subscriptionChecker = false;
                    $subscriptions = UserSubscription::find()->where(['user_id' => \Yii::$app->user->id, 'type' => 'membership'])->all();

                    foreach ($subscriptions as $sub) {
                        if ($sub->ref_id == Membership::Talent || $sub->ref_id == Membership::TalentWithProduct) {
                            $paymentGateway = UserPaymentService::paymentGateway($totalPrice, $params);
                            $subscriptionChecker = true;
                            break;
                        }
                    }
                    if (!$subscriptionChecker) {
                        $paymentGateway = [];
                        $paymentGateway['code'] = 200;
                    }
                } else {
                    $paymentGateway = UserPaymentService::paymentGateway($totalPrice, $params);
                    $subscriptionChecker = true;
                }


                if ($paymentGateway['code'] == 200) {
                    // Free MemberShip User Purchase Plan
                    if ($dataObj['basic'] != NULL) {
//                        UserSubscription::updateAll(['status' => 'in-active'], ['user_id' => $user->id, 'type' => 'membership']);
                        UserPaymentService::createSubscription('membership', $dataObj['basic'], $user->id, $group_id);
                        if ($membership_id == Membership::TalentWithProduct)
                            Product::updateAll(['is_locked' => 0], ['created_by' => $user->id, 'is_locked' => 1]);

                        UserProfileImage::updateAll(['is_locked' => 0], ['user_id' => $user->id, 'show_on' => UserProfileImage::$show_on_image_gallery, 'is_locked' => 1]);
                        UserProfileImage::updateAll(['is_locked' => 0], ['user_id' => $user->id, 'show_on' => UserProfileImage::$show_on_video_gallery, 'is_locked' => 1]);
                    }

                    // Purchase Addons
                    if ($dataObj['addons'] != null) {
                        foreach ($dataObj['addons'] as $addon) {
                            $item = MsItems::findOne($addon);
                            UserPaymentService::createSubscription('addons', $item->id, $user->id, $group_id);
                        }
                    }
                    if ($subscriptionChecker) {
                        $payment = $this->actionPayment($group_id, $totalPrice, $paymentGateway);
                        if ($payment) {
                            $session->remove('addonsTotalPrice');
                            $session->remove('addons');
                            SquarePaymentService::$checkoutCard = 0;
                            Yii::$app->session->setFlash('success', 'Subscription add successfully');
                            return $this->redirect(['/profile']);
                        }
                        Yii::$app->session->setFlash('error', 'payment Not Save In Database');
                    } else {
                        $session->remove('addonsTotalPrice');
                        $session->remove('addons');
                        SquarePaymentService::$checkoutCard = 0;
                        Yii::$app->session->setFlash('success', 'Subscription add successfully');
                        return $this->redirect(['/profile']);
                    }
                }
                if (isset($paymentGateway['message']) && $paymentGateway['code'] == 400) {
                    Yii::$app->session->setFlash('error', $paymentGateway['message']);
                }
            }
            if ($dataObj['basic'] == null && $dataObj['addons'] == null) {
                Yii::$app->session->setFlash('error', 'Please select any Subscription');
            }
        }

        return $this->render('subscription', [
                    'basic' => $basic,
                    'addons' => isset($addons) ? $addons : null,
                    'free' => $free,
                        // 'membership' => $membership,
        ]);
    }

    public function actionAddtalent() {
        $this->layout = 'main';
        $user = \Yii::$app->user->identity->getUser();
        $access = UserAccessService::checkByKey($user, 'TALENT');
        if ($access) {
            $limit = UserAccessService::checkLimitByMsItem($user->id, $access, 'TALENT');
            if ($limit) {
                $id = Yii::$app->user->id;
                if (Yii::$app->request->post()) {
                    $form_data = Yii::$app->request->post();
                    $model = new UserTalent;
                    $model->attributes = $form_data;
                    if (isset($form_data['group_gender']) && $form_data['group_gender'] != '') {
                        $model->gender = $form_data['group_gender'];
                    }
                    $model->user_id = $id;
                    $model->created_at = time();
                    $model->created_by = $id;
                    $model->modified_at = time();
                    $model->modified_by = $id;
                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', 'Add Talent successfully');
                        return $this->redirect(['/profile']);
                    }
                }
                return $this->render('add_talent', [
                            'industries' => IndustryService::getAll()
                ]);
            } else {
                Yii::$app->session->setFlash('error', 'Limit end in your current plan, Please subscribe Addons for more.');
                return $this->redirect(['/profile']);
            }
        } else {
            Yii::$app->session->setFlash('error', 'Access denied in your current plan, Please update your membership plan.<br><a href="' . Html::encode(Url::to(['/subscription'])) . '">Click Here</a>');
            return $this->redirect(['/profile']);
        }
    }

    public function actionProfile() {
        $this->layout = 'main';
        $model = User::findOne(Yii::$app->user->id);
        $talents = UserTalent::find()->where(['user_id' => Yii::$app->user->id])->all();
        $user_addresses = UserAddressService::userAddress('get', Yii::$app->user->id);
        $subscriptions = $model->getSubscribedItems();
        $referral = UserMlmService::getReferralCountAndLevel($model);
        return $this->render('my_profile', [
                    'model' => $model,
                    'talents' => $talents,
                    'user_addresses' => $user_addresses,
                    'subscriptions' => $subscriptions,
                    'referral' => $referral,
        ]);
    }

    public function actionAddaddress() {
        $this->layout = 'main';
        $model = new UserAddress;
        $countries = UserAddressService::getCountries();
        if (Yii::$app->request->post()) {
            $result = UserAddressService::userAddress('post', Yii::$app->user->id, null, $model, Yii::$app->request->post());
            if ($result) {
                Yii::$app->session->setFlash('success', 'Address Added successfully');
                return $this->redirect(['profile']);
            }
        }
        return $this->render('add_address', array('model' => $model, 'countries' => $countries));
    }

    public function actionUpdateaddress($id) {
        $this->layout = 'main';
        $model = UserAddressService::userAddress('get', null, $id);
        $countries = UserAddressService::getCountries();
        if (Yii::$app->request->post()) {
            $result = UserAddressService::userAddress('put', Yii::$app->user->id, null, $model, Yii::$app->request->post());
            if ($result) {
                Yii::$app->session->setFlash('success', 'Address Updated successfully');
                return $this->redirect(['profile']);
            }
        }
        return $this->render('update_address', array('model' => $model, 'countries' => $countries));
    }

    public function actionUpdateprofile() {
        $this->layout = 'main';
        $model = User::findOne(Yii::$app->user->id);
        $countries = UserAddressService::getCountries();
        if (Yii::$app->request->post()) {
            $form_data = Yii::$app->request->post();
            $model->name = $form_data['name'];
            $model->phone = $form_data['phone'];
            $model->city = $form_data['city'];
            $model->state = $form_data['state'];
            $model->country = $form_data['country'];
            $model->updated_at = time();
            if ($model->update()) {
                Yii::$app->session->setFlash('success', 'Profile update successfully');
                return $this->redirect(['profile']);
            }
        }
        return $this->render('update_profile', [
                    'model' => $model, 'countries' => $countries
        ]);
    }

    public function actionUpload() {
        $model = new PhotosForm;
        $user = \Yii::$app->user->identity->getUser();
        if (\Yii::$app->request->post()) {
            $key = \Yii::$app->request->post()['key'];
            $showon = \Yii::$app->request->post()['showon'];
            $access = UserAccessService::checkByKey($user, $key);
            if ($access) {
                $limit = UserAccessService::checkLimitByMsItem($user->id, $access, $key);
                if ($limit) {
                    GalleryService::upload($model, $showon);
                } else {
                    Yii::$app->session->setFlash('error', 'Limit end in your current plan, Please subscribe Addons for more.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Access denied in your current plan, Please update your membership plan.<br><a href="' . Html::encode(Url::to(['/subscription'])) . '">Click Here</a>');
            }
        }
        return $this->redirect(['/gallery']);
    }

    public function actionUploadprofile() {
        $model = new PhotosForm;
        if (\Yii::$app->request->post()) {
            if ($model->validate()) {
                $path = UserProfileImage::getfullPath(UserProfileImage::$show_on_profile);
                $name = \Yii::$app->security->generateRandomString();
                if (is_dir($path)) {
                    UserProfileImage::deletePreviousFile($path);
                }
                if (!is_dir($path)) {
                    BaseFileHelper::createDirectory($path);
                }
                if ($model->files[0]->saveAs($path . $name . '.' . $model->files[0]->extension)) {
                    $image = UserProfileImage::createImage($name, $model->files[0]->extension, UserProfileImage::$show_on_profile);
                    if ($image->save()) {
                        return $this->redirect(\Yii::$app->request->referrer);
                    } else {
                        
                    }
                    Yii::$app->session->setFlash('error', 'save error');
                    return $this->redirect(\Yii::$app->request->referrer);
                }
                Yii::$app->session->setFlash('error', 'file saveAs error');
                return $this->redirect(\Yii::$app->request->referrer);
            }
            $allErrors = '';
            foreach ($model->getErrors() as $file) {
                foreach ($file as $error) {
                    $allErrors .= $error . '  ';
                }
            }
            Yii::$app->session->setFlash('error', $allErrors);
        }
        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionUploadbanner() {
        $model = new PhotosForm;
        if (\Yii::$app->request->post()) {
            if ($model->validate()) {
                $path = UserProfileImage::getfullPath(UserProfileImage::$show_on_banner);
                $name = \Yii::$app->security->generateRandomString();
                if (is_dir($path)) {
                    UserProfileImage::deletePreviousFile($path);
                }
                if (!is_dir($path)) {
                    BaseFileHelper::createDirectory($path);
                }
                if ($model->files[0]->saveAs($path . $name . '.' . $model->files[0]->extension)) {
                    $image = UserProfileImage::createImage($name, $model->files[0]->extension, UserProfileImage::$show_on_banner);
                    if ($image->save()) {
                        return $this->redirect(\Yii::$app->request->referrer);
                    }
                    Yii::$app->session->setFlash('error', 'Image Not Save In Model');
                    return $this->redirect(\Yii::$app->request->referrer);
                }
                Yii::$app->session->setFlash('error', 'Image Not Save In Server');
                return $this->redirect(\Yii::$app->request->referrer);
            }
            $allErrors = '';
            foreach ($model->getErrors() as $file) {
                foreach ($file as $error) {
                    $allErrors .= $error . '  ';
                }
            }
            Yii::$app->session->setFlash('error', $allErrors);
        }

        return $this->redirect(\Yii::$app->request->referrer);
    }

    private function actionPayment($ref_id, $amount, $paymentGateway) {
        $params = [];
        $params['user_id'] = Yii::$app->user->id;
        $params['amount'] = $amount;
        $params['currency_id'] = 1;
        $params['type'] = 'subscription';
        $params['ref_id'] = $ref_id;
        if (isset($paymentGateway['transection_id'])) {
            $params['transection_id'] = $paymentGateway['transection_id'];
            $params['status'] = 1;
        }
        $payment = UserPaymentService::createPayment($params);
        if ($payment) {
            return TRUE;
        }
        return FALSE;
    }
}
