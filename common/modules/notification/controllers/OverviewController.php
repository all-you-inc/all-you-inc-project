<?php

namespace common\modules\notification\controllers;

use common\components\behaviors\AccessControl;
use common\components\Controller;
use common\modules\notification\models\forms\FilterForm;
use common\modules\notification\models\Notification;
use Yii;
use yii\data\Pagination;
use yii\db\IntegrityException;

/**
 * ListController
 *
 * @package common.modules_core.notification.controllers
 * @since 0.5
 */
class OverviewController extends Controller
{
    const PAGINATION_PAGE_SIZE = 20;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'acl' => [
                'class' => AccessControl::class,
            ]
        ];
    }

    /**
     * Returns a List of all notifications for the session user
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return Yii::$app->user->loginRequired();
        }

        $pageSize = static::PAGINATION_PAGE_SIZE;
        $notifications = [];

        $filterForm = new FilterForm();
        $filterForm->load(Yii::$app->request->get());

        $query = Notification::findGrouped();

        if ($filterForm->hasFilter()) {
            $query->andFilterWhere(['not in', 'class', $filterForm->getExcludeClassFilter()]);
        } else {
            return $this->render('index', [
                'filterForm' => $filterForm,
                'pagination' => null,
                'notifications' => $notifications
            ]);
        }

        $countQuery = clone $query;
        $pagination = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => $pageSize]);

        //Reset pagegination after new filter set
        if (Yii::$app->request->post()) {
            $pagination->setPage(0);
        }

        $query->offset($pagination->offset)->limit($pagination->limit);

        foreach ($query->all() as $notificationRecord) {
            /* @var $notificationRecord \common\modules\notification\models\Notification */

            try {
                $baseModel = $notificationRecord->getBaseModel();

                if($baseModel->validate()) {
                    $notifications[] = $baseModel;
                } else {
                    throw new IntegrityException('Invalid base model found for notification');
                }

            } catch (IntegrityException $ex) {
                $notificationRecord->delete();
                Yii::warning('Deleted inconsistent notification with id ' . $notificationRecord->id . '. ' . $ex->getMessage());
            }
        }

        return $this->render('index', [
            'notifications' => $notifications,
            'filterForm' => $filterForm,
            'pagination' => $pagination
        ]);
    }

}
