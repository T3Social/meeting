<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

namespace humhub\modules\meeting\models\forms;

use Yii;
use humhub\modules\user\models\User;
use humhub\modules\meeting\models\MeetingItemModerator;

class MeetingItemForm extends \yii\base\Model
{
    /**
     * @var \humhub\modules\meeting\models\MeetingItem
     */
    public $model;

    /**
     * @var string[]
     */
    public $inputModerators = [];

    /**
     * @var string form duration string e.g. 1:30 (hh:mm)
     */
    public $duration;

    /**
     * @var array
     */
    public $fileList = [];

    public function init()
    {
        if(!$this->model->isNewRecord) {
            $this->duration = $this->durationToString($this->model->duration);
            $this->inputModerators = $this->model->moderatorUsers;
        }

        parent::init();
    }

    public function rules()
    {
        return [
            ['duration', 'match', 'pattern' => '/[0-9]+:{1}[0-9]{2}/'],
            ['fileList', 'safe'],
            ['inputModerators', 'each', 'rule' => ['string']]
        ];
    }

    public function attributeLabels()
    {
        return [
            'inputModerators' => Yii::t('MeetingModule.views_index_index', 'Moderators'),
            'duration' =>  Yii::t('MeetingModule.base', 'Duration')
        ];
    }

    public function attributeHints()
    {
        return [
            'duration' =>  Yii::t('MeetingModule.base', 'Duration in <strong>hh:mm</strong> format ')
        ];
    }

    public function load($data, $formName = null)
    {
        return parent::load($data) && $this->model->load($data);
    }

    public function save()
    {
        if(!$this->validate()) {
            return false;
        }

        $this->model->duration = $this->durationFromString();

        //Reset old  values
        $this->model->begin = null;
        $this->model->end = null;

        if($this->model->save()) {
            $this->model->fileManager->attach($this->fileList);
            $this->saveModerators();
            return true;
        }
        return false;
    }

    private function saveModerators()
    {
        foreach($this->model->moderators as $moderator) {
            $moderator->delete();
        }

        if(empty($this->inputModerators)) {
            return;
        }

        foreach ($this->inputModerators as $guid) {
            $user = User::findOne(['guid' => $guid]);
            if($user) {
                $this->model->meeting->addParticipant($user);
                $moderator = new MeetingItemModerator(['user_id' => $user->id, 'meeting_item_id' => $this->model->id]);
                $moderator->save();
            }
        }
    }

    private function durationToString($duration)
    {
        if($duration) {
            $hours = intval($duration / 60);
            $minutes = $duration % 60;
            return $hours.':'.(($minutes < 10) ? '0' : '').$minutes;
        }
        return null;
    }

    private function durationFromString()
    {
        if(empty($this->duration)) {
            return null;
        }

        list($hours, $minutes) = explode(':', $this->duration);
        $hours = intval($hours);
        $minutes = intval($minutes);
        return ($hours * 60) + $minutes;
    }
}