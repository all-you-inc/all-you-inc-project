<?php


namespace common\modules\notification\controllers;

use Yii;
use common\modules\admin\components\Controller;
use common\modules\notification\models\forms\NotificationSettings;

/**
 * AdminController is for system administrators to set activity e-mail defaults.
 *
 * @since 1.2
 * @author Luke
 */
class AdminController extends Controller
{
    /**
     * @inheritdoc
     */
    public function getAccessRules()
    {
        return [
            ['permissions' => \common\modules\admin\permissions\ManageSettings::class]
        ];
    }

    public function actionDefaults()
    {
        $this->subLayout = '@admin/views/layouts/setting';

        $form = new NotificationSettings();
        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            $this->view->saved();
        }

        return $this->render('defaults', ['model' => $form]);
    }
}
