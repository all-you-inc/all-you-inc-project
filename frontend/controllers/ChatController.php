<?php

namespace frontend\controllers;

use shop\useCases\ContactService;
use Yii;
use yii\web\Controller;
use shop\forms\ContactForm;
use common\models\chat\Thread;
use common\models\chat\ThreadParticipant;
use common\models\chat\Message;
use common\services\ChatService;
use common\services\NotificationService;

class ChatController extends Controller {

    private $service;

    public function __construct($id, $module, ContactService $service, $config = []) {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function actionMessages() {
        $this->layout = 'main';
        $thread_id = isset(Yii::$app->request->get()['thread-id']) ? Yii::$app->request->get()['thread-id'] : NULL;
        $user_id = isset(Yii::$app->request->get()['user-id']) ? Yii::$app->request->get()['user-id'] : NULL;
        $keyword = isset(Yii::$app->request->get()['keyword']) ? Yii::$app->request->get()['keyword'] : NULL;
        //For Notification seen update
        $message_id = isset(Yii::$app->request->get()['message-id']) ? Yii::$app->request->get()['message-id'] : NULL; 
        if ($message_id)
            NotificationService::updateNotificationSeenBySource(\Yii::$app->user->id, 'common\models\chat\Message', $message_id);

        if (Yii::$app->request->post()) {
            $data = Yii::$app->request->post();
            if ($data['submit'] == 'thread') {
                if (isset($data['Message']['title']) && $data['Message']['title'] != NULL && isset($data['Message']['body']) && $data['Message']['body'] != NULL && isset($data['Message']['user_id']) && $data['Message']['user_id'] != NULL) {
                    $thread = ChatService::createThread($data['Message']);
                    if ($thread instanceof Thread)
                        Yii::$app->session->setFlash('success', 'Messsage send successfully');
                    return $this->redirect(['/messages?user-id=' . $user_id . '&thread-id=' . $thread->id]);
                } else {
                    Yii::$app->session->setFlash('error', 'Messsage send Unsuccessfull!!');
                }
            } elseif ($data['submit'] == 'message') {
                if (isset($data['Message']['body']) && $data['Message']['body'] != NULL && isset($data['Message']['thread_id']) && $data['Message']['thread_id'] != NULL && isset($data['Message']['user_id']) && $data['Message']['user_id'] != NULL) {
                    $message = ChatService::createMessage($data['Message']);
                    if ($message instanceof Message)
                        Yii::$app->session->setFlash('success', 'Messsage send successfully');
                } else {
                    Yii::$app->session->setFlash('error', 'Unsuccessfull messsage not send !!!');
                }
            }
        }
        $messages = ChatService::getThreadMessages($thread_id, $keyword);
        $threads = ChatService::getAllThread($user_id);
        $thread = ChatService::getThreadById($thread_id);
        $users = ChatService::getAllTalentUsers();
        ChatService::updateThreadUnreadCount($thread_id, \Yii::$app->user->id);

        return $this->render('messages', ['messages' => $messages, 'threads' => $threads, 'thread' => $thread, 'users' => $users]);
    }

    public function actionThreads() {
        $this->layout = 'main';
        $threads = ChatService::getAllThread(\Yii::$app->user->id);
        return $this->render('threads', ['threads' => $threads]);
    }

    public function actionDelete($thread_id) {
        if (ChatService::DeleteThread($thread_id)) {
            Yii::$app->session->setFlash('success', 'Successfully Deleted');
        }
        Yii::$app->session->setFlash('error', 'Some thing went wrong..');
        return $this->redirect('/threads');
    }

}
