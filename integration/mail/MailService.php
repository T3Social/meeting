<?php

use humhub\modules\mail\models\Message;
use humhub\modules\mail\models\MessageEntry;
use humhub\modules\mail\models\UserMessage;
use humhub\modules\meeting\models\MeetingItem;
use humhub\modules\user\models\User;
use yii\db\Expression;
use yii\helpers\Json;
use humhub\modules\meeting\Module;

/**
 * Created by PhpStorm.
 * User: kingb
 * Date: 18.09.2018
 * Time: 11:20
 */
class MailService extends \yii\base\Component
{
    /**
     * @param MeetingItem $item
     * @param User $recipient
     * @return bool
     * @throws \yii\db\Exception
     */
    public function sendItem(MeetingItem $item, User $recipient)
    {
        $author = Yii::$app->user->identity;

        $transaction = \Yii::$app->db->beginTransaction();

        $conversation = $this->newConversation($item, $author);

        if (!$conversation->save()) {
            Yii::error(Json::encode($conversation->errors));
            $transaction->rollBack();
            return false;
        }

        $messageEntry = $this->newMessage($conversation, $author, $item);

        if (!$messageEntry->save()) {
            Yii::error(Json::encode($messageEntry->errors));
            $transaction->rollBack();
            return false;
        }

        $userMessage = $this->newUserMessage($conversation, $recipient);

        if (!$userMessage->save()) {
            Yii::error(Json::encode($userMessage->errors));
            $transaction->rollBack();
            return false;
        }

        try {
            $conversation->notify($recipient);
        } catch (\Exception $e) {
            Yii::error('Could not send notification e-mail to: ' . $recipient->getDisplayName() . ". Error:" . $e->getMessage());
        }

        $userMessage = $this->newOriginatorMessage($conversation, $author);
        if (!$userMessage->save()) {
            Yii::error(Json::encode($userMessage->errors));
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();
        return true;
    }

    private function newConversation(MeetingItem $item, User $author)
    {
        return new Message(['title' => $item->title]);
    }

    private function newMessage(Message $conversation, User $author, MeetingItem $item)
    {
        return new MessageEntry([
            'message_id' => $conversation->id,
            'user_id' => $author->id,
            'content' => $item->description
        ]);
    }

    private function newUserMessage(Message $conversation, User $recipient)
    {
        return new UserMessage([
            'message_id' => $conversation->id,
            'user_id' => $recipient->id
        ]);
    }

    private function newOriginatorMessage(Message $conversation, User $originator)
    {
        return new UserMessage([
            'message_id' => $conversation->id,
            'user_id' => $originator->id,
            'is_originator' => 1,
            'last_viewed' => new Expression('NOW()')
        ]);
    }

}