<?php

namespace common\services;

use Yii;
use common\models\chat\Message;
use common\models\chat\ThreadParticipant;
use common\models\chat\Thread;
use shop\entities\User\User;
use yii\data\Pagination;
use yii\db\ActiveQuery;
use common\models\chat\MessageRead;
use common\modules\customnotification\components\MessageNotification;

class ChatService {

    public static function getThreadMessages($thread_id, $keyword = '', $pageSize = 6) {
        $result = [];
        if ($thread_id) {
            $dataProvider = Message::find()->where(['thread_id' => $thread_id, 'is_deleted' => 0]);
            if ($keyword != '')
                $dataProvider = $dataProvider->andWhere(['LIKE', 'body', $keyword]);

            $countQuery = clone $dataProvider;
            $pages = new Pagination(['totalCount' => $countQuery->count()]);
            $pages->defaultPageSize = $pageSize;
            $dataProvider = $dataProvider->offset($pages->offset)->limit($pages->limit)->all();
            $result['data'] = $dataProvider;
            $result['pages'] = $pages;
        }
        return $result;
    }

    public static function getThreadById($thread_id) {
        return Thread::findOne($thread_id);
    }

    public static function DeleteThread($thread_id) {
        $thread = Thread::findOne($thread_id);
        if ($thread instanceof Thread) {
            if ($thread->created_by == \Yii::$app->user->id) {
                $thread->is_deleted = 1;
                $thread->update();
            }
            ThreadParticipant::updateAll(['is_deleted' => 1], ['thread_id' => $thread_id, 'user_id' => \Yii::$app->user->id]);
            return TRUE;
        }
        return FALSE;
    }

    public static function getParticipantByThreadId($thread_id, $user_id) {
        return ThreadParticipant::find()->where('thread_id=' . $thread_id . ' AND user_id!=' . $user_id . ' AND is_deleted = 0')->all();
    }

    public static function getAllTalentUsers() {
        return User::find()->innerJoin('user_talent', 'user_talent.user_id = users.id')->all();
    }

    public static function updateThreadUnreadCount($thread_id, $user_id) {
        if ((isset($thread_id) && $thread_id != NULL) && (isset($user_id) && $user_id != NULL)) {
            $messages = Message::find()->where('is_deleted = 0 AND user_id !=' . $user_id . ' AND thread_id =' . $thread_id)->all();
            if ($messages) {
                foreach ($messages as $message) {
                    $check = MessageRead::find()->where(['message_id' => $message->id])->one();
                    if (!$check) {
                        self::createMessageRead($message->id, $user_id);
                    }
                }
            }
            return TRUE;
        }
        return FALSE;
    }

    public static function getAllThreadUnreadCount($user_id) {
        $dataProvider = self::getAllThreadQuery($user_id)->all();
        $count = 0;
        foreach ($dataProvider as $data) {
            $count += $data['messageCount'] - $data['readCount'];
        }
        return $count;
    }

    public static function getAllThreadQuery($user_id) {
        $dataProvider = Thread::find();
        $dataProvider = $dataProvider->select(['chat_thread.*,'
            . 'COUNT(chat_message_read.id) AS readCount,'
            . 'COUNT(chat_message.id) AS messageCount'
        ]);
        $dataProvider = $dataProvider->leftJoin('chat_thread_participant', 'chat_thread_participant.thread_id = chat_thread.id');
        $dataProvider = $dataProvider->leftJoin('chat_message', 'chat_message.thread_id = chat_thread_participant.thread_id AND chat_message.is_deleted = 0 AND chat_message.user_id != ' . $user_id);
        $dataProvider = $dataProvider->leftJoin('chat_message_read', 'chat_message.id = chat_message_read.message_id AND chat_message_read.is_deleted = 0 AND chat_message_read.user_id = ' . $user_id);
        $dataProvider = $dataProvider->where([
            'chat_thread.is_deleted' => 0,
            'chat_thread_participant.is_deleted' => 0,
            'chat_thread_participant.user_id' => $user_id
        ]);
        $dataProvider = $dataProvider->groupBy(['chat_thread_participant.thread_id'])->orderBy("chat_thread.modified_at DESC");

        return $dataProvider;
    }

    public static function getAllThread($user_id, $pageSize = 10) {
        $result = [];
        if ($user_id) {
            $dataProvider = self::getAllThreadQuery($user_id);
            $countQuery = clone $dataProvider;
            $pages = new Pagination(['totalCount' => $countQuery->count()]);
            $pages->defaultPageSize = $pageSize;
            $dataProvider = $dataProvider->offset($pages->offset)->limit($pages->limit)->all();
            if ($dataProvider) {
                $threads = [];
                foreach ($dataProvider as $key => $data) {

                    $threads[$key]['id'] = $data->id;
                    $threads[$key]['title'] = isset($data->title) ? $data->title : '-';
                    $threads[$key]['description'] = isset($data->description) ? $data->description : '-';
                    $threads[$key]['creator'] = isset($data->created->name) ? $data->created->name : '-';
                    $threads[$key]['created_by'] = $data->created;
                    $threads[$key]['created_at'] = $data->created_at;
                    $threads[$key]['unread_count'] = $data['messageCount'] - $data['readCount'];
                    $threads[$key]['participants'] = $data->chatThreadParticipants;
                }
                $result['data'] = $threads;
                $result['pages'] = $pages;
            }
        }

        return $result;
    }

    public static function createMessage($form_data) {
//        dd($form_data);
        $model = new Message();
        if ($form_data) {
            $model->attributes = $form_data;
            $model->created_at = time();
            $model->modified_at = time();
            $model->created_by = $form_data['user_id'];
            $model->modified_by = $form_data['user_id'];
            if ($model->save()) {
                $participants = self::getParticipantByThreadId($form_data['thread_id'], $form_data['user_id']);
                foreach ($participants as $participant) {
                    $from_user = User::findOne($form_data['user_id']);
                    $to_user = User::findOne($participant->user_id);
//                    $source = Thread::findOne($model->thread_id);
                    MessageNotification::instance()->from($from_user)->about($model)->send($to_user);
                }
                return $model;
            }
        }
        return FALSE;
    }

    public static function createThreadParticipant($thread_id, $user_id, $creator_id) {
        $model = new ThreadParticipant();
        if ($thread_id && $user_id && $creator_id) {
            $model->thread_id = $thread_id;
            $model->user_id = $user_id;
            $model->created_at = time();
            $model->modified_at = time();
            $model->created_by = $creator_id;
            $model->modified_by = $creator_id;
            if ($model->save()) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public static function createMessageRead($message_id, $user_id) {
        $model = new MessageRead();
        if ($message_id && $user_id) {
            $model->message_id = $message_id;
            $model->user_id = $user_id;
            $model->read_at = time();
            $model->created_at = time();
            $model->modified_at = time();
            $model->created_by = $user_id;
            $model->modified_by = $user_id;
            if ($model->save()) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public static function createThread($form_data) {
        $model = new Thread();
        if ($form_data) {
            $model->attributes = $form_data;
            $model->created_at = time();
            $model->modified_at = time();
            $model->created_by = $form_data['creator_id'];
            $model->modified_by = $form_data['creator_id'];
            if ($model->save()) {
                self::createThreadParticipant($model->id, $form_data['creator_id'], $form_data['creator_id']);
                self::createThreadParticipant($model->id, $form_data['user_id'], $form_data['creator_id']);
                $form_data['user_id'] = $form_data['creator_id'];
                $form_data['thread_id'] = $model->id;
                self::createMessage($form_data);
                return $model;
            }
        }
        return FALSE;
    }

}
