<?php

namespace humhub\modules\meeting\models;

use DateTime;
use humhub\modules\content\widgets\richtext\RichText;
use humhub\modules\tasks\models\Task;
use Yii;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\meeting\permissions\ManageMeetings;
use humhub\modules\user\models\User;

/**
 * This is the model class for table "meeting_item".
 *
 * The followings are the available columns in table 'meeting_item':
 * @property integer $id
 * @property integer $meeting_id
 * @property string $begin
 * @property string $end
 * @property string $title
 * @property string $description
 * @property string $notes
 * @property string $external_moderators
 * @property integer $sort_order
 * @property integer $duration
 *
 * @property MeetingTask[] $meetingTasks
 */
class MeetingItem extends ContentActiveRecord
{
    /**
     * @inheritdocs
     */
    protected $managePermission = ManageMeetings::class;

    /**
     * @inheritdocs
     */
    protected $streamChannel = null;

    public $inputModerators;

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'meeting_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['meeting_id', 'title'], 'required'],
            [['meeting_id', 'sort_order', 'duration'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['inputModerators', 'description', 'external_moderators', 'notes'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['editMinutes'] = ['notes'];
        return $scenarios;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => Yii::t('MeetingModule.meetingitem', 'Title'),
            'description' => Yii::t('MeetingModule.meetingitem', 'Description'),
            'duration' => Yii::t('MeetingModule.meetingitem', 'Duration (hh:mm)'),
            'notes' => Yii::t('MeetingModule.meetingitem', 'Minutes'),
        ];
    }

    /**
     * Creates a duplicated model by removing id and notes isNewRecord to true.
     * Note this method is only intended to render a MeetingForm and not for saving the actual duplicate.
     */
    public function duplicate(Meeting $meeting)
    {
        // Fetch participant users relation before resetting id!
        $duplicate = new MeetingItem($this->content->container, $this->content->visibility, [
            'meeting_id' => $meeting->id,
            'begin' => $this->begin,
            'end' => $this->end,
            'title' => $this->title,
            'description' => $this->description,
            'external_moderators' => $this->external_moderators,
            'duration' => $this->duration,
            'sort_order' => $this->sort_order
        ]);

        return $duplicate;
    }

    public function getTimeRangeText()
    {
        $formatter = Yii::$app->formatter;

        if(is_string($this->begin)) {
            return substr($this->begin, 0, 5) . " - " . substr($this->end, 0, 5);
        }

        if($this->begin instanceof DateTime) {
            return $formatter->asTime($this->begin, 'short')." - ".$formatter->asTime($this->end, 'short');
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        try {
            Yii::$app->search->update($this->meeting);
        } catch (\Throwable $e) {
            Yii::error($e);
        }

        RichText::postProcess($this->description, $this);
        if (isset($this->notes)) {
            RichText::postProcess($this->notes, $this);
        }

        parent::afterSave($insert, $changedAttributes);
    }

    public function beforeDelete()
    {
        foreach (MeetingItemModerator::findAll(['meeting_item_id' => $this->id]) as $moderator) {
            $moderator->delete();
        }

        foreach (MeetingTask::findAll(['meeting_item_id' => $this->id]) as $meetingTask) {
            $meetingTask->delete();
        }

        return parent::beforeDelete();
    }

    public function getMeeting()
    {
        return $this->hasOne(Meeting::class, ['id' => 'meeting_id']);
    }

    public function getMeetingTasks()
    {
        return $this->hasMany(MeetingTask::class, ['meeting_item_id' => 'id'])->leftJoin('task', ['meeting_task.task_id' => 'task.id'])->orderBy(['task.end_datetime' => SORT_ASC]);
    }

    public function getModerators()
    {
        return $this->hasMany(MeetingItemModerator::class, ['meeting_item_id' => 'id']);
    }

    public function getModeratorUsers()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])->via('moderators');
    }

    public function getUrl()
    {
        return $this->content->container->createUrl('/meeting/index/view', ['id' => $this->meeting_id]);
    }

    public function getContentName()
    {
        return Yii::t('MeetingModule.base', "Agenda Entry");
    }

    public function getContentDescription()
    {
        return $this->title;
    }
}
