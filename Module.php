<?php

namespace humhub\modules\meeting;

use Yii;
use humhub\modules\meeting\permissions\ManageMeetings;
use humhub\modules\meeting\models\Meeting;
use humhub\modules\meeting\models\MeetingTask;
use humhub\modules\space\models\Space;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\components\ContentContainerModule;

class Module extends ContentContainerModule
{
    public $activateMailIntegration = true;

    /**
     * @inheritdoc
     */
    public function getContentContainerTypes()
    {
        return [
            Space::class,
        ];
    }

    public function isMailIntegrationActive()
    {
        return $this->activateMailIntegration && Yii::$app->hasModule('mail');
    }

    public function getPermissions($contentContainer = null)
    {
        if ($contentContainer && $contentContainer->moduleManager->isEnabled($this->id)) {
            return [
                new ManageMeetings()
            ];
        }

        return parent::getPermissions($contentContainer);
    }

    /**
     * @inheritdoc
     */
    public function getContentContainerDescription(ContentContainerActiveRecord $container)
    {
        return Yii::t('MeetingModule.base', 'Adds a meeting manager to this space.');
    }

    /**
     * @inheritdoc
     */
    public function disable()
    {
        foreach (Meeting::find()->all() as $meeting) {
            $meeting->delete();
        }
        
        parent::disable();
    }

    /**
     * @inheritdoc
     */
    public function disableContentContainer(ContentContainerActiveRecord $container)
    {
        parent::disableContentContainer($container);

        foreach (Meeting::find()->contentContainer($container)->all() as $meeting) {
            $meeting->delete();
        }
    }
}
