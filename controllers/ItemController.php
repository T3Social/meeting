<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

namespace humhub\modules\meeting\controllers;

use humhub\components\behaviors\AccessControl;
use humhub\modules\content\permissions\ManageContent;
use humhub\modules\file\libs\FileHelper;
use humhub\modules\meeting\models\forms\ItemDrop;
use humhub\modules\meeting\models\forms\MeetingItemForm;
use humhub\modules\meeting\models\forms\SendItemMailForm;
use humhub\modules\meeting\models\ShiftMeetingChoose;
use humhub\modules\meeting\permissions\ManageMeetings;
use humhub\modules\tasks\models\forms\TaskForm;
use humhub\modules\tasks\widgets\TaskEditModal;
use Yii;
use yii\db\Exception;
use yii\helpers\Url;
use yii\web\HttpException;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\file\models\File;
use humhub\modules\content\models\Content;
use humhub\modules\meeting\models\Meeting;
use humhub\modules\meeting\models\MeetingItem;
use humhub\modules\meeting\models\MeetingTask;
use humhub\modules\tasks\models\Task;

/**
 * Description of IndexController
 *
 * @author and1, luke
 */
class ItemController extends ContentContainerController
{
    /**
     * @inheritdoc
     */
    public $strictGuestMode = true;

    public function getAccessRules()
    {
        return [['permission' => ManageMeetings::class]];
    }

    public function actionEdit($id = null, $meetingId = null)
    {
        $item = null;

        if (!$id && !$meetingId) {
            throw new HttpException(404);
        } else if (!$id) {
            $meeting = Meeting::find()->contentContainer($this->contentContainer)->where(['meeting.id' => $meetingId])->one();
            if ($meeting) {
                $item = $meeting->newItem();
            }
        } else {
            $item = MeetingItem::find()->contentContainer($this->contentContainer)->where(['meeting_item.id' => $id])->one();
        }

        if (!$item) {
            throw new HttpException(404);
        }

        $form = new MeetingItemForm(['model' => $item]);

        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            return $this->htmlRedirect($this->contentContainer->createUrl('/meeting/index/view', ['id' => $item->meeting_id]));
        }

        return $this->renderAjax("editItem", [
            'itemForm' => $form,
            'saveUrl' => $this->contentContainer->createUrl('/meeting/item/edit', ['meetingId' => $meetingId, 'id' => $item->id]),
            'deleteUrl' => $this->contentContainer->createUrl('/meeting/item/delete', ['id' => $item->id])
        ]);
    }

    public function actionEditProtocol($id)
    {
        $item = MeetingItem::find()->contentContainer($this->contentContainer)->where(['meeting_item.id' => $id])->one();
        $item->scenario = 'editMinutes';

        if ($item->load(Yii::$app->request->post()) && $item->save()) {
            $item->fileManager->attach(Yii::$app->request->post('fileUploaderHiddenGuidField'));
            return $this->htmlRedirect($this->contentContainer->createUrl('/meeting/index/view', ['id' => $item->meeting_id]));
        }

        return $this->renderAjax("editProtocol", ['item' => $item, 'meetingId' => $item->meeting_id, 'contentContainer' => $this->contentContainer]);
    }

    public function actionShift($id)
    {
        $chooseModel = new ShiftMeetingChoose(['itemId' => $id, 'contentContainer' => $this->contentContainer]);

        if ($chooseModel->load(Yii::$app->request->post()) && $chooseModel->shiftItem()) {
            return $this->htmlRedirect($this->contentContainer->createUrl('/meeting/index/view', ['id' => $chooseModel->meetingId]));
        }

        return $this->renderAjax('shift', [
            'submitUrl' => $this->contentContainer->createUrl('/meeting/item/shift', ['id' => $id]),
            'createNewUrl' => $this->contentContainer->createUrl('/meeting/index/duplicate', ['id' => $chooseModel->getItem()->meeting_id, 'itemId' => $id]),
            'chooseModel' => $chooseModel
        ]);
    }

    public function actionDelete($id)
    {
        $this->forcePostRequest();

        /* @var MeetingItem $item */
        $item = MeetingItem::find()->contentContainer($this->contentContainer)->where(['meeting_item.id' => $id])->one();
        if (!$item) {
            return $this->htmlRedirect($this->contentContainer->createUrl('/meeting/index'));
        }

        $item->delete();

        return $this->htmlRedirect($this->contentContainer->createUrl('/meeting/index/view', ['id' => $item->meeting_id]));
    }

    /**
     * @param $id
     * @throws HttpException
     */
    public function actionSend($id)
    {
        if(!Yii::$app->hasModule('mail')) {
            throw new HttpException(400);
        }

        /* @var MeetingItem $item */
        $item = MeetingItem::find()->contentContainer($this->contentContainer)->readable()->where(['meeting_item.id' => $id])->one();
        if(!$item) {
            throw new HttpException(404);
        }

        $model = new \humhub\modules\mail\models\forms\CreateMessage([
            'title' => $item->title,
            'message' =>  "## Description:\n\n".$item->description."\n\n## Protocol:\n\n".$item->notes
        ]);

        return $this->renderAjax('@mail/views/mail/create', [
            'model' => $model
        ]);
    }

    public function actionDrop($meetingId)
    {
        $model = new ItemDrop(['meetingId' => $meetingId]);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $result = [];
            foreach ($model->meeting->getItemsPopulated() as $item) {
                $result[$item->id] = [
                    'sortOrder' => $item->sort_order,
                    'time' => $item->getTimeRangeText(),
                ];
            }

            return $this->asJson([
                'success' => true,
                'items' => $result
            ]);
        }

        return $this->asJson([
            'success' => false,
            'error' => 'Items cannot be sorted, probably it was delete, please refresh this page!'
        ]);
    }
}
