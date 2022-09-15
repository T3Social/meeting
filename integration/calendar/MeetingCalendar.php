<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

namespace humhub\modules\meeting\integration\calendar;

use DateTime;
use humhub\modules\meeting\models\Meeting;
use humhub\widgets\Label;
use Yii;
use yii\base\Component;
use yii\helpers\Url;

/**
 * Created by PhpStorm.
 * User: buddha
 * Date: 14.09.2017
 * Time: 12:28
 *
 * @todo change base class back to BaseObject after v1.3 stable
 */

class MeetingCalendar extends Component
{
    /**
     * Default color of meeting type calendar items.
     */
    const DEFAULT_COLOR = '#2c99d6';

    const ITEM_TYPE_KEY = 'meeting';

    /**
     * @param $event \humhub\modules\calendar\interfaces\CalendarItemTypesEvent
     * @return mixed
     */
    public static function addItemTypes($event)
    {
        $event->addType(static::ITEM_TYPE_KEY, [
            'title' => Yii::t('MeetingModule.base', 'Meeting'),
            'color' => static::DEFAULT_COLOR,
            'icon' => 'fa-calendar-o'
        ]);
    }

    /**
     * @param $event \humhub\modules\calendar\interfaces\CalendarItemsEvent
     * @throws \Throwable
     */
    public static function addItems($event)
    {
        /* @var $meetings Meeting[] */
        $meetings = MeetingCalendarQuery::findForEvent($event);

        $items = [];
        foreach ($meetings as $meeting) {
            $items[] = [
                'start' => $meeting->getBeginDateTime(),
                'end' => $meeting->getEndDateTime(),
                'uid' => $meeting->uid,
                'title' => $meeting->title,
                'editable' => true,
                'icon' => 'fa-calendar-o',
                'viewUrl' => $meeting->content->container->createUrl('/meeting/index/modal', ['id' => $meeting->id, 'cal' => true]),
                'openUrl' => $meeting->content->getUrl(),
                'updateUrl' => $meeting->content->container->createUrl('/meeting/index/calendar-update', ['id' => $meeting->id]),
            ];
        }

        $event->addItems(static::ITEM_TYPE_KEY, $items);
    }

}