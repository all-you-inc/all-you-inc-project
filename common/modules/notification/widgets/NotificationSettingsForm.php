<?php
namespace common\modules\notification\widgets;

use common\modules\notification\models\forms\NotificationSettings;
use common\modules\space\models\Space;
use Yii;
use yii\widgets\ActiveForm;

/**
 * Description of NotificationSettingForm
 *
 * @author buddha
 */
class NotificationSettingsForm extends \yii\base\Widget
{
    /**
     * @var ActiveForm
     */
    public $form;
    
    /**
     * @var NotificationSettings
     */
    public $model;
    
    /**
     * @var boolean
     */
    public $showSpaces = true;
    
    /**
     * Spaces which should be added by default to the space chooser result as suggestion
     * @var Space[]
     */
    private $defaultSpaces = [];

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->defaultSpaces = Yii::$app->notification->getNonNotificationSpaces($this->model->user);

        
        return $this->render('notificationSettingsForm', [
            'form' => $this->form,
            'model' => $this->model,
            'showSpaces' => $this->showSpaces,
            'defaultSpaces' => $this->defaultSpaces
        ]);
    }
}
