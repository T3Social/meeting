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
 * Date: 01.07.2017
 * Time: 17:18
 */

namespace humhub\modules\meeting\models\forms;


use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\meeting\models\Meeting;
use humhub\modules\meeting\models\MeetingParticipant;
use humhub\modules\meeting\permissions\ManageMeetings;
use Yii;
use yii\base\Model;

class MeetingFilter extends Model
{
    /**
     * @var ContentContainerActiveRecord
     */
    public $contentContainer;

    /**
     * @var string
     */
    public $title;

    /**
     * @var int
     */
    public $past = 1;

    /**
     * @var int
     */
    public $participant;

    /**
     * @var int
     */
    public $own;

    public function rules()
    {
        return [
            ['title', 'string'],
            [['past', 'participant', 'own'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('MeetingModule.models_forms_MeetingFilter', 'Filter meetings'),
            'past' => Yii::t('MeetingModule.models_forms_MeetingFilter', 'Only past meetings'),
            'participant' => Yii::t('MeetingModule.models_forms_MeetingFilter', 'I\'m participating'),
            'own' => Yii::t('MeetingModule.models_forms_MeetingFilter', 'Created by me'),
        ];
    }

    public function query()
    {
        $user = Yii::$app->user->getIdentity();

        if($this->past) {
            $query = Meeting::findPastMeetings($this->contentContainer);
        } else {
            $query = Meeting::findReadable($this->contentContainer);
        }

        if(!empty($this->title)) {
            $query->andWhere(['like', 'title', $this->title]);
        }

        if($this->participant) {
            $subQuery = MeetingParticipant::find()
                ->where('meeting_participant.meeting_id=meeting.id')
                ->andWhere(['meeting_participant.user_id' => $user->id]);
            $query->andWhere(['exists', $subQuery]);
        }

        if($this->own) {
            $query->andWhere(['content.created_by' => $user->contentcontainer_id]);
        }



        return $query;
    }
}