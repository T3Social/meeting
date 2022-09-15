<?php
/**
 * Created by PhpStorm.
 * User: Buddha
 * Date: 21.06.2017
 * Time: 13:59
 */

namespace humhub\modules\meeting\widgets;


use humhub\modules\meeting\models\Meeting;
use humhub\modules\meeting\models\MeetingItem;
use humhub\modules\user\models\fieldtype\DateTime;
use humhub\widgets\JsWidget;

class MeetingItemWidget extends JsWidget
{
    /**
     * @inheritdoc
     */
    public $jsWidget = 'meeting.Item';

    /**
     * @inheritdoc
     */
    public $init = true;

    /**
     * @var Meeting
     */
    public $meeting;

    /**
     * @var MeetingItemWidget
     */
    public $item;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $contentContainer = $this->meeting->content->container;
        return $this->render('meetingItem', [
            'options' => $this->getOptions(),
            'meeting' => $this->meeting,
            'item' => $this->item,
            'canEdit' =>  $this->meeting->content->canEdit(),
            'editUrl' => $contentContainer->createUrl('/meeting/item/edit', ['id' => $this->item->id, 'meetingId' => $this->meeting->id]),
            'editProtocolUrl' => $contentContainer->createUrl('/meeting/item/edit-protocol', ['id' => $this->item->id, 'meetingId' => $this->meeting->id]),
            'contentContainer' => $this->meeting->content->container,
        ]);
    }

    public function getData()
    {
        return [
            'item-id' => $this->item->id,
            'sort-order' => $this->item->sort_order
        ];
    }
}