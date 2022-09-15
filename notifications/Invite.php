<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\meeting\notifications;

use Yii;
use humhub\modules\notification\components\BaseNotification;
use yii\helpers\Html;

/**
 * Notifies an admin about reported content
 *
 * @since 0.5
 */
class Invite extends BaseNotification
{

    /**
     * @inheritdoc
     */
    public $moduleId = 'meeting';

    /**
     * @inheritdoc
     */
    public $viewName = "invite";
    
    public function html() {
        return Yii::t('MeetingModule.views_notifications_invited', '{userName} invited you to {meeting}.', [
            '{userName}' => '<strong>' . Html::encode($this->originator->displayName) . '</strong>',
            '{meeting}' => '<strong>' . $this->getContentInfo($this->source) . '</strong>'
        ]);
    }
}
