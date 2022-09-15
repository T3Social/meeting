<?php

namespace humhub\modules\meeting\models;

use Yii;
use humhub\modules\user\models\User;

/**
 * This is the model class for table "meeting_participant".
 *
 * The followings are the available columns in table 'meeting_participant':
 * @property integer $id
 * @property integer $meeting_id
 * @property integer $user_id
 * @property string $name
 */
class MeetingParticipant extends \humhub\components\ActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'meeting_participant';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['meeting_id', 'required'],
            [['meeting_id', 'user_id'], 'integer'],
            ['name', 'string', 'max' => 255],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'meeting_id' => 'Meeting',
            'user_id' => 'User',
            'name' => Yii::t('MeetingModule.meetingparticipant', 'Name'),
        ];
    }

    public function getUser(): ?User
    {
        if ($this->user_id) {
            return User::findOne(['id' => $this->user_id]);
        }
        return null;
    }

}
