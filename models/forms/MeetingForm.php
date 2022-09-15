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
 * Date: 13.07.2017
 * Time: 22:00
 */

namespace humhub\modules\meeting\models\forms;

use DateTime;
use DateTimeZone;
use humhub\libs\DbDateValidator;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\models\Content;
use humhub\modules\meeting\comp\DbDateValidatorPatched;
use humhub\modules\meeting\models\Meeting;
use humhub\modules\meeting\widgets\TaskAddon;
use Yii;
use yii\base\Model;

class MeetingForm extends Model
{

    /**
     * @var Meeting
     */
    public $meeting;

    /**
     * @var integer MeetingItem id used in case we shift an item to a new meeting
     */
    public $itemId;

    /**
     * @var integer Meeting id in case we duplicate a meeting
     */
    public $duplicateId;

    /**
     * @var Meeting instance of meeting to duplicate
     */
    public $duplicate;

    /**
     * @var int whether or not to duplicate items if a meeting duplicate is given
     */
    public $duplicateItems = 1;

    /**
     * @var string startDate of the meeting
     */
    public $startDate;

    /**
     * @var string endDate of the meeting
     */
    public $endDate;

    /**
     * @var string time zone of the meeting
     */
    public $timeZone;

    /**
     * @var string start time of the meeting
     */
    public $startTime;

    /**
     * @var string end time of the meeting
     */
    public $endTime;

    /**
     * @var boolean defines if the request came from a calendar
     */
    public $cal;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!$this->timeZone) {
            $this->timeZone = Yii::$app->formatter->timeZone;
        }

        if ($this->meeting) {
            $this->translateToUserTimeZone();
        }

        $this->initDefaultMeetingDates();
    }

    private function initDefaultMeetingDates()
    {
        if ($this->meeting && !$this->meeting->isNewRecord) {
            return;
        }

        $this->startDate = $this->endDate = date('Y-m-d');
        $this->startTime = date('H') . ':00:00';
        $this->endTime = (date('H') + 1) . ':00:00';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['timeZone'], 'in', 'range' => DateTimeZone::listIdentifiers()],
            [['startDate'], DbDateValidator::class,  'format' => Yii::$app->formatter->dateInputFormat, 'timeAttribute' => 'startTime', 'timeZone' => $this->timeZone],
            [['endDate'], DbDateValidator::class,  'format' => Yii::$app->formatter->dateInputFormat, 'timeAttribute' => 'endTime', 'timeZone' => $this->timeZone],
            [['startTime', 'endTime'], 'date', 'type' => 'time', 'format' => $this->getTimeFormat(), 'locale' => $this->getTimeLocale()],
            [['duplicateId', 'itemId'], 'integer'],
            ['duplicateItems', 'integer', 'min' => 0, 'max' => 1],
            [['duplicate'], 'validateDuplicate'],
        ];
    }


    public function getTimeFormat()
    {
        return Yii::$app->formatter->isShowMeridiem() ? 'h:mm a' : 'php:H:i';
    }

    public function getTimeLocale()
    {
        return Yii::$app->formatter->isShowMeridiem() ? 'en-US' : Yii::$app->formatter->locale;
    }

    public function validateDuplicate()
    {
        if($this->duplicateId && !$this->duplicate) {
            throw new \InvalidArgumentException('Meeting to duplicate not found!');
        }

        if($this->duplicate && $this->duplicate->content->contentcontainer_id != $this->meeting->content->contentcontainer_id) {
            throw new \InvalidArgumentException('Tried to duplicate a meeting from another space!');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'endTime' => Yii::t('MeetingModule.meeting', 'End'),
            'startTime' => Yii::t('MeetingModule.meeting', 'Begin'),
            'startDate' => Yii::t('MeetingModule.meeting', 'Date'),
            'duplicateItems' => Yii::t('MeetingModule.meeting', 'Duplicate agenda entries'),
        ]);
    }

    public function getTitle()
    {
        if($this->itemId) {
            return Yii::t('MeetingModule.base', '<strong>Shift</strong> agenda entry to new meeting');
        }

        if($this->duplicateId) {
            return Yii::t('MeetingModule.base', '<strong>Duplicate</strong> meeting');
        }

        if($this->meeting->isNewRecord) {
           return Yii::t('MeetingModule.views_index_edit', '<strong>Create</strong> new meeting');
        }

        return Yii::t('MeetingModule.views_index_edit', '<strong>Edit</strong> meeting');
    }

    /**
     * Instantiates a new meeting for the given ContentContainerActiveRecord.
     *
     * @param ContentContainerActiveRecord $contentContainer
     * @throws \yii\base\Exception
     */
    public function createNew(ContentContainerActiveRecord $contentContainer)
    {
        $this->meeting = new Meeting($contentContainer, Content::VISIBILITY_PRIVATE);
    }

    /**
     * Loads this model and the meeting model with the given data.
     *
     * @inheritdoc
     *
     * @param array $data
     * @param null $formName
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function load($data, $formName = null)
    {
        // Make sure the timeZone is loaded prior to validation rule built
        if(isset($data[$this->formName()])) {
            $this->timeZone = $data[$this->formName()]['timeZone'] ?? $this->timeZone;
        }

        parent::load($data);

        if($this->duplicateId) {
            $this->duplicate = Meeting::findOne($this->duplicateId);
        }

        return $this->meeting->load($data);
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        // Before DbDateValidator translates the time zones from user to system time zone we use the cloned startDate as endDate but with the endTime
        if (!empty($this->startDate)) {
            $this->endDate = $this->startDate;
        }
        return true;
    }

    /**
     * Validates and saves the meeting instance.
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function save()
    {
        $isNew = $this->meeting->isNewRecord;

        if (!$this->validate()) {
            $this->meeting->validate();
            return false;
        }

        $this->meeting->date = $this->startDate;

        Yii::$app->formatter->timeZone = Yii::$app->timeZone;
        $this->meeting->begin = Yii::$app->formatter->asTime(new DateTime($this->startDate), 'php:H:i:s');
        $this->meeting->end = Yii::$app->formatter->asTime(new DateTime($this->endDate), 'php:H:i:s');
        Yii::$app->i18n->autosetLocale();

        if ($this->meeting->save()) {
            if($this->duplicate && $this->duplicateItems) {
                foreach ($this->duplicate->items as $itemToDuplicate) {
                    $itemToDuplicate->duplicate($this->meeting)->save();
                }
            }

            // If an itemId is given we shift the given item to the current meeting
            if ($this->itemId) {
                $this->meeting->shiftItem($this->itemId);
            }
            return true;
        }

        return false;
    }

    /**
     * Translates startDate/time and endDate/time of the given meeting from system time zone to given time zone.
     */
    public function translateToUserTimeZone()
    {
        $startTime = $this->getMeetingDateTime($this->meeting->begin);
        $endTime = $this->getMeetingDateTime($this->meeting->end);

        Yii::$app->formatter->timeZone = $this->timeZone;

        $this->startDate = Yii::$app->formatter->asDateTime($startTime, 'php:Y-m-d');
        $this->startTime = $this->getAsTime($startTime);

        $this->endDate = Yii::$app->formatter->asDateTime($endTime, 'php:Y-m-d');
        $this->endTime = $this->getAsTime($endTime);

        Yii::$app->i18n->autosetLocale();
    }

    private function getAsTime($date)
    {
        $locale = Yii::$app->formatter->locale;

        Yii::$app->formatter->locale = $this->getTimeLocale();

        $result = Yii::$app->formatter->asTime($date, $this->getTimeFormat());

        Yii::$app->formatter->locale = $locale;
        return $result;
    }

    private function getMeetingDateTime($timeVal)
    {
        return new DateTime($this->meeting->date . ' ' . $timeVal, new DateTimeZone(Yii::$app->timeZone));
    }

    public function getFormattedStartDate()
    {
        return $this->meeting->getFormattedStartDate($this->isUserTimeZone() ? null : $this->timeZone);
    }

    public function getFormattedBeginTime()
    {
        // We just change the formatter timezone if it another timezone was selected by user.
        return $this->meeting->getFormattedBeginTime($this->isUserTimeZone() ? null : $this->timeZone);
    }

    public function getFormattedEndTime($timeZone = null)
    {
        return $this->meeting->getFormattedEndTime($this->isUserTimeZone() ? null : $this->timeZone);
    }

    public function isUserTimeZone()
    {
        return $this->timeZone === $this->getUserTimeZone();
    }

    public function getUserTimeZone()
    {
        return Yii::$app->formatter->timeZone;
    }

    public function getSubmitUrl()
    {
        return $this->meeting->content->container->createUrl('/meeting/index/edit', [
            'id' => $this->meeting->id,
            'itemId' => $this->itemId,
            'cal' => $this->cal
        ]);
    }

    public function getDeleteUrl()
    {
        return $this->meeting->content->container->createUrl('delete', [
            'id' => $this->meeting->id,
            'cal' => $this->cal
        ]);
    }

    public function getParticipantPickerUrl()
    {
        return $this->meeting->content->container->createUrl('/meeting/index/participant-picker', ['id' => $this->meeting->id]);
    }

    public function updateTime($start = null, $end = null)
    {
        $startDate = new DateTime($start, new DateTimeZone($this->getUserTimeZone()));
        $endDate = new DateTime($end, new DateTimeZone($this->getUserTimeZone()));

        Yii::$app->formatter->timeZone = Yii::$app->timeZone;

        // Note we ignore the end date (just use the time) since a meeting can't span over several days
        $this->meeting->date = Yii::$app->formatter->asDatetime($startDate, 'php:Y-m-d H:i:s');
        $this->meeting->begin = Yii::$app->formatter->asTime($startDate, 'php:H:i:s');
        $this->meeting->end = Yii::$app->formatter->asTime($endDate, 'php:H:i:s');

        Yii::$app->i18n->autosetLocale();

        return $this->meeting->save();
    }

}
