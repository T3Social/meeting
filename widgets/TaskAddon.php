<?php

namespace humhub\modules\meeting\widgets;

use humhub\modules\meeting\models\Meeting;
use humhub\modules\meeting\models\MeetingTask;
use humhub\modules\meeting\models\MeetingItem;

class TaskAddon extends \yii\base\Widget
{

    public $task;

    public function run()
    {

        $meetingTask = MeetingTask::findOne(array('task_id' => $this->task->id));
        if ($meetingTask === null) {
            return;
        }

        $meetingItem = MeetingItem::findOne(array('id' => $meetingTask->meeting_item_id));
        if ($meetingItem == null) {
            return;
        }

        $meeting = Meeting::findOne(array('id' => $meetingItem->meeting_id));
        if ($meeting == null) {
            return;
        }

        return $this->render('relatedMeeting', array(
                    'space' => $this->task->content->container,
                    'meeting' => $meeting
        ));
    }

}

?>
