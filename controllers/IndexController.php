<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\meeting\controllers;

use Yii;
use yii\web\HttpException;
use humhub\modules\meeting\models\forms\MeetingFilter;
use humhub\modules\meeting\models\forms\MeetingForm;
use humhub\modules\meeting\models\MeetingParticipant;
use humhub\modules\meeting\permissions\ManageMeetings;
use humhub\modules\meeting\widgets\MeetingListView;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\UserPicker;
use humhub\widgets\ModalClose;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\meeting\models\Meeting;

/**
 * Description of IndexController
 *
 * @author luke, buddh4
 */
class IndexController extends ContentContainerController
{
    /**
     * @inheritdoc
     */
    public $strictGuestMode = true;

    public function getAccessRules()
    {
        return [
            ['permission' => ManageMeetings::class,
                'actions' => [
                    'participant-picker',
                    'duplicate',
                    'send-invite-notifications'.
                    'edit',
                    'delete',
                    'calendar-update'
                ]
            ],
            [
                'userGroup' => Space::USERGROUP_MEMBER,
                'actions' => [
                    'index',
                    'print',
                    'filter-meetings',
                    'get-ics',
                    'modal',
                    'share',
                    'view',
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $meetings = Meeting::findPendingMeetings($this->contentContainer)->all();

        return $this->render("index", [
                    'pendingMeetings' => $meetings,
                    'canEdit' => $this->canEdit(),
                    'contentContainer' => $this->contentContainer,
                    'filter' => new MeetingFilter(['contentContainer' => $this->contentContainer])
        ]);
    }

    public function actionView($id)
    {
        $meeting = Meeting::find()->contentContainer($this->contentContainer)->where(['meeting.id' => $id])->one();

        if(!$meeting) {
            throw new HttpException(404);
        }

        if(!$meeting->content->canView() && !$meeting->isParticipant()) {
            throw new HttpException(403);
        }

        return $this->render("meeting", [
                    'meeting' => $meeting,
                    'contentContainer' => $this->contentContainer
        ]);
    }

    public function actionModal($id, $cal)
    {
        $meeting = Meeting::find()->contentContainer($this->contentContainer)->where(['meeting.id' => $id])->one();

        if(!$meeting) {
            throw new HttpException(404);
        }

        if(!$meeting->content->canView()) {
            throw new HttpException(403);
        }

        return $this->renderAjax('modal', [
            'meeting' => $meeting,
            'editUrl' => $this->contentContainer->createUrl('/meeting/index/edit', ['id' => $meeting->id, 'cal' => $cal]),
            'canManageEntries' => $meeting->content->canEdit(),
            'contentContainer' => $this->contentContainer,
        ]);
    }

    public function actionParticipantPicker($id = null, $keyword = '')
    {
        if($id) {
            $subQuery = MeetingParticipant::find()->where(['meeting_participant.meeting_id' => $id])->andWhere('meeting_participant.user_id=user.id');
            $query = $this->getSpace()->getMembershipUser()->where(['not exists', $subQuery]);
        } else {
            $query = $this->getSpace()->getMembershipUser();
        }

        return $this->asJson(UserPicker::filter([
            'keyword' => $keyword,
            'query' => $query,
            'fillUser' => true
        ]));
    }

    public function actionFilterMeetings()
    {
        $filter = new MeetingFilter(['contentContainer' => $this->contentContainer]);
        $filter->load(Yii::$app->request->post());

        return $this->asJson([
            'success' => true,
            'output' => MeetingListView::widget(['filter' => $filter])
        ]);
    }

    public function actionPrint($id)
    {
        $meeting = Meeting::find()->contentContainer($this->contentContainer)->where(['meeting.id' => $id])->one();

        return $this->renderPartial("print", [
                    'meeting' => $meeting,
                    'contentContainer' => $this->contentContainer
        ]);
    }

    public function actionEdit($id = null, $itemId = null, $cal = false)
    {
        if (!$id) {
            $meetingForm = new MeetingForm(['itemId' => $itemId, 'cal' => $cal]);
            $meetingForm->createNew($this->contentContainer);
        } else {
            $meetingForm = new MeetingForm([
                'meeting' => Meeting::find()->contentContainer($this->contentContainer)->where(['meeting.id' => $id])->one(),
                'itemId' => $itemId,
                'cal' => $cal
            ]);
        }

        if(!$meetingForm->meeting) {
            throw new HttpException(404);
        }

        if ($meetingForm->load(Yii::$app->request->post()) && $meetingForm->save()) {
            if($cal) {
                return ModalClose::widget(['saved' => true]);
            }

            return $this->htmlRedirect($this->contentContainer->createUrl('view', ['id' => $meetingForm->meeting->id]));
        }

        return $this->renderAjax('edit', ['meetingForm' => $meetingForm]);
    }

    public function actionDuplicate($id, $itemId = null)
    {
        $meeting = Meeting::find()->contentContainer($this->contentContainer)->where(['meeting.id' => $id])->one();
        $meeting->duplicated();

        // We reset the duplicate id in case this is a shift item action, so we prevent other items from beeing copied.
        if($itemId) {
            $id = null;
        }

        return $this->renderAjax('edit', ['meetingForm' => new MeetingForm(['meeting' => $meeting, 'duplicateId' => $id, 'itemId' => $itemId])]);
    }

    public function actionDelete($id, $cal = false)
    {
        $this->forcePostRequest();

        $meeting = Meeting::find()->contentContainer($this->contentContainer)->where(['meeting.id' => $id])->one();
        if ($meeting) {
            $meeting->delete();
        } else {
            throw new HttpException(404);
        }

        if(!$cal) {
            return $this->htmlRedirect($this->contentContainer->createUrl('index'));
        } else {
            return ModalClose::widget();
        }
    }

    public function canEdit()
    {
        return $this->contentContainer->getPermissionManager()->can(new ManageMeetings());
    }

    public function actionShare($id)
    {
        $meeting = Meeting::find()->contentContainer($this->contentContainer)->where(['meeting.id' => $id])->one();

        if(!$meeting->content->canView()) {
            throw new HttpException(403);
        }

        return $this->renderAjax('share', ['meeting' => $meeting, 'contentContainer' => $this->contentContainer, 'canEdit' => $this->canEdit()]);
    }

    public function actionGetIcs($id, $type = null)
    {
        $meeting = Meeting::find()->contentContainer($this->contentContainer)->where(['meeting.id' => $id])->one();

        if(!$meeting) {
            throw new HttpException(404);
        }

        if(!$meeting->content->canView()) {
            throw new HttpException(403);
        }

        return $this->renderPartial('getICS', ['meeting' => $meeting, 'type' => $type, 'contentContainer' => $this->contentContainer]);
    }

    public function actionCalendarUpdate($id)
    {
        $this->forcePostRequest();

        $meeting = Meeting::find()->contentContainer($this->contentContainer)->where(['meeting.id' => $id])->one();

        if (!$meeting) {
            throw new HttpException('404');
        }

        if (!($meeting->content->canEdit())) {
            throw new HttpException('403');
        }

        $meetingForm = new MeetingForm(['meeting' => $meeting]);

        if ($meetingForm->updateTime(Yii::$app->request->post('start'), Yii::$app->request->post('end'))) {
            return $this->asJson(['success' => true]);
        }

        throw new HttpException(400, "Could not save! " . print_r($meetingForm->getErrors()));
    }

    public function actionSendInviteNotifications($id)
    {
        $meeting = Meeting::find()->contentContainer($this->contentContainer)->where(['meeting.id' => $id])->one();

        if(!$meeting) {
            throw new HttpException(404);
        }

        if(!$this->canEdit()) {
            throw new HttpException(403);
        }

        $meeting->inviteUser();

        return $this->asJson([
            'success' => true
        ]);
    }

}
