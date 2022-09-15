<?php

namespace humhub\modules\meeting\models;

/**
 * This is the model class for table "meeting_item".
 *
 * The followings are the available columns in table 'meeting_item':
 * @property integer $id
 * @property integer $meeting_item_id
 * @property integer $task_id
 *
 * @property \humhub\modules\tasks\models\Task $task
 */
class MeetingTask extends \humhub\components\ActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'meeting_task';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            [['meeting_item_id', 'task_id'], 'required'],
        ];
    }

    public function getTask()
    {
        return $this->hasOne(\humhub\modules\tasks\models\Task::class, ['id' => 'task_id']);
    }

    public function getUrl()
    {
        return $this->task->getUrl();
    }

    public function getTitle()
    {
        return $this->task->title;
    }

    public function isCompleted()
    {
        return $this->task->isCompleted();
    }

    public function hasScheduling()
    {
        return $this->task->scheduling;
    }

    public function isOverdue()
    {
        return $this->task->isOverdue();
    }

    public function getEndDate()
    {
        return $this->task->end_datetime;
    }

    public function canEdit()
    {
        return $this->task->content->canEdit();
    }

    /**
     * @return \humhub\modules\user\models\User[]
     */
    public function getAssignedUsers()
    {
        return $this->task->taskAssignedUsers;
    }

}
