<?php
/**
 * Created by PhpStorm.
 * User: kingb
 * Date: 18.09.2018
 * Time: 16:15
 */

namespace humhub\modules\meeting\models\forms;


use humhub\modules\meeting\models\MeetingItem;
use humhub\modules\user\models\User;
use MailService;
use yii\base\Model;
use yii\web\HttpException;

class SendItemMailForm extends Model
{
    private $item;

    /**
     * @var string
     */
    public $userGuid;

    /**
     * @var int
     */
    public $itemId;

    /**
     * @var
     */
    public $contentContainer;

    /**
     * @throws HttpException
     * @throws \yii\base\Exception
     */
    public function init()
    {
        parent::init();

        $this->item = MeetingItem::find()->contentContainer($this->contentContainer)->readable()->where(['meeting_item_id.id' => $this->itemId])->one();
        if(!$this->item) {
            throw new HttpException(404);
        }
    }

    public function rules()
    {
        return [['user', 'safe']];
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function save() {
        $recipient = User::findOne(['guid' => $this->userGuid]);
        $mailService = new MailService();
        return $mailService->sendItem($this->item, $recipient);
    }

}