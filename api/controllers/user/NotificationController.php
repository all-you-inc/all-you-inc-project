<?php

namespace api\controllers\user;

use Yii;
use yii\rest\Controller;
use common\services\NotificationService;
use api\helpers\DataHelper;
use common\modules\notification\models\Notification;
use common\models\notification\NotificationEvents;

class NotificationController extends Controller
{
    private $service;

    public function __construct($id, $module, NotificationService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }
    
    public function actionGetNotifications() {
        $params = \Yii::$app->request->get();
        $limit = isset($params['limit']) ? $params['limit'] : 10;
        $page = isset($params['page']) ? $params['page'] : 1;
        $data = [];
        
        if (!$this->validatePositiveInteger($limit) || !$this->validatePositiveInteger($page)) {
            return [
                'message' => 'Invalid arguments.',
                'status' => 400,
            ];
        }
        
        $query = Notification::find()->where(['user_id' => Yii::$app->user->id])->orderBy(['created_at' => SORT_DESC]);
        $clone = clone $query;
        $total = $clone->count();
        
        if ($total == 0) {
            return [
                'message' => 'No notification found for this user.',
                'status' => 404,
            ];
        }
        
        $pagination = new \yii\data\Pagination(['totalCount' => $total]);
        $pagination->defaultPageSize = $limit;
        $notifications = $query->offset($pagination->offset)->limit($pagination->limit)->all();
        
        foreach ($notifications as $notification) {
            $data[] = DataHelper::serliazeNotification($notification);
        }
        
        return [
            'status' => 200,
            'currentPage' => $pagination->getPage() + 1,
            'totalPages' => $pagination->getPageCount(),
            'data' => $data
        ];
    }
    
    public function actionMarkNotificationsSeen() {
        $unseenNotifications = $this->service->getUserUnseenNotifications(Yii::$app->user->id);
        $count = count($unseenNotifications);
        
        if ($unseenNotifications) {
            $this->service->updateUserNotificationSeens(Yii::$app->user->id);
        }
        
        return [
            'message' => "$count notification(s) successfully marked as seen.",
            'status' => 200,
        ];
    }
    
    public function actionGetNotificationSettings() {
        $user_id = Yii::$app->user->id;
        $data = [];
        
        $notificationSettings = (new \yii\db\Query())
                ->select('ne.id as id, ne.event as event, un.email as email, un.web as web, un.mobile as mobile')
                ->from('notification_events ne')
                ->leftJoin('user_notification un', "un.user_id = $user_id AND ne.id = un.notification_event_id")
                ->all();
        
        foreach ($notificationSettings as $notificationSetting) {
            $data[] = DataHelper::serializeNotificationSetting($notificationSetting);
        }
        
        return [
            'status' => 200,
            'data' => $data
        ];
    }
    
    public function actionUpdateNotificationSettings() {
        $post = \Yii::$app->request->post();
        $data = [];
        
        if (!isset($post['event']) || empty($post['event'])) {
            return [
                'message' => 'Invalid or missing arguments.',
                'status' => 400
            ];
        }
        
        foreach ($post['event'] as $item) {
            if ($item['email'] === '1' || $item['web'] === '1' || $item['mobile'] === '1') {
                $data[$item['id']] = [];
                if ($item['email'] === '1') { $data[$item['id']]['email'] = 'on'; }
                if ($item['web'] === '1') { $data[$item['id']]['web'] = 'on'; }
                if ($item['mobile'] === '1') { $data[$item['id']]['mobile'] = 'on'; }
            }
        }
        
        NotificationService::postUserNotificationEvents($data, Yii::$app->user->id);
        return [
            'message' => 'Notification preferences updated successfully.',
            'status' => 200
        ];
    }
    
    private function validatePositiveInteger($var) {
        return filter_var($var, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    }
}