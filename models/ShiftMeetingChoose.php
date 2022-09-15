<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

/**
 * Created by PhpStorm.
 * User: buddha
 * Date: 02.07.2017
 * Time: 21:24
 */

namespace humhub\modules\meeting\models;


use Yii;
use yii\base\Model;

class ShiftMeetingChoose extends Model
{
    public $meetingId;

    public $itemId;

    /**
     * @var Meeting[]
     */
    public $meetings = [];

    public $contentContainer;

    private $item;

    private $items;

    public function rules()
    {
        return [
            ['meetingId', 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'meetingId' => Yii::t('MeetingModule.views_item_shift', 'Choose upcoming meeting')
        ];
    }

    public function getItem()
    {
        if(!$this->item) {
            $this->item = MeetingItem::find()->contentContainer($this->contentContainer)->andWhere(['meeting_item.id' => $this->itemId])->one();
        }
        return $this->item;
    }

    public function getItems()
    {
        if($this->items === null) {
            $item = $this->getItem();
            $this->meetings = Meeting::findPendingMeetings($this->contentContainer)->andWhere(['<>', 'meeting.id', $item->meeting_id])->all();

            $this->items = [];
            foreach ($this->meetings as $meeting) {
                $this->items[$meeting->id] = $meeting->title.' - '.$meeting->getFormattedStartDate();
            }
        }

        return $this->items;
    }

    public function shiftItem()
    {
        if(!$this->validate()) {
            return false;
        }

        // Load new meeting and shift item.
        $meeting = Meeting::find()->contentContainer($this->contentContainer)->where(['meeting.id' => $this->meetingId])->one();
        return $meeting->shiftItem($this->itemId);
    }
}