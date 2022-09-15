<?php

namespace humhub\modules\meeting\models;

use Yii;
use humhub\modules\user\models\User;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "meeting_item_moderator".
 *
 * The followings are the available columns in table 'meeting_item_moderator':
 * @property integer $id
 * @property integer $meeting_item_id
 * @property integer $user_id
 * @property string $name
 */
class MeetingItemModerator extends \humhub\components\ActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'meeting_item_moderator';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['meeting_item_id', 'required'],
            [['meeting_item_id', 'user_id'], 'integer'],
            ['name', 'string', 'max' => 255],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'name' => 'Name', Yii::t('MeetingModule.meetingitemmoderator', 'Name'),
        );
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
