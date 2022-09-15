<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

namespace humhub\modules\meeting;

use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\meeting\integration\calendar\MeetingCalendar;
use humhub\modules\meeting\models\MeetingItem;
use humhub\modules\meeting\models\MeetingTask;
use humhub\modules\meeting\widgets\TaskAddon;
use humhub\modules\space\models\Space;
use Yii;


/**
 * Created by PhpStorm.
 * User: buddha
 * Date: 14.09.2017
 * Time: 12:12
 */
class Events
{
    /**
     * @param $event \humhub\modules\calendar\interfaces\CalendarItemTypesEvent
     */
    public static function onGetCalendarItemTypes($event)
    {
        try {
            /* @var ContentContainerActiveRecord $contentContainer */
            $contentContainer = $event->contentContainer;

            if(!$contentContainer || $contentContainer->moduleManager->isEnabled('meeting')) {
                MeetingCalendar::addItemTypes($event);
            }
        } catch (\Throwable $e) {
            Yii::error($e);
        }
    }

    /**
     * @param $event \humhub\modules\calendar\interfaces\CalendarItemsEvent;
     * @throws \Throwable
     */
    public static function onFindCalendarItems($event)
    {
        try {
            /* @var ContentContainerActiveRecord $contentContainer */
            $contentContainer = $event->contentContainer;

            if (!$contentContainer || $contentContainer->moduleManager->isEnabled('meeting')) {
                MeetingCalendar::addItems($event);
            }
        } catch (\Throwable $e) {
            Yii::error($e);
        }
    }

    /**
     * Callback to validate module database records.
     *
     * @param Event $event
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public static function onIntegrityCheck($event)
    {
        $integrityController = $event->sender;
        $integrityController->showTestHeadline("Meeting Module (" . MeetingTask::find()->count() . " task relations)");

        foreach (MeetingTask::find()->all() as $meetingTask) {
            if (!$meetingTask->task) {
                if ($integrityController->showFix("Delete meeting_task " . $meetingTask->id . " without existing task relation!")) {
                    $meetingTask->delete();
                }
            }
        }
    }


    public static function onSpaceMenuInit($event)
    {
        /* @var $space \humhub\modules\space\models\Space */

        try {
            /* @var Space $space */
            $space = $event->sender->space;

            if ($space->moduleManager->isEnabled('meeting') && $space->isMember()) {

                $event->sender->addItem([
                    'label' => Yii::t('MeetingModule.base', 'Meetings'),
                    'group' => 'modules',
                    'url' => $space->createUrl('//meeting/index'),
                    'icon' => '<i class="fa fa-calendar-o"></i>',
                    'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'meeting'),
                ]);
            }
        } catch (\Throwable $e) {
            Yii::error($e);
        }
    }

    public static function onTaskDelete($event)
    {
        try {
            foreach (MeetingTask::find()->where(['task_id' => $event->sender->id])->all() as $meetingTask) {
                $meetingTask->delete();
            }
        } catch (\Throwable $e) {
            Yii::error($e);
        }
    }

    public static function onTaskWallEntry($event)
    {
        try {
            if (get_class($event->sender->object) == 'humhub\modules\tasks\models\Task') {
                $event->sender->addWidget(TaskAddon::class, ['task' => $event->sender->object], ['sortOrder' => 2]);
            }
        } catch (\Throwable $e) {
            Yii::error($e);
        }
    }

}