<?php


namespace humhub\modules\meeting\controllers;

use Yii;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\meeting\models\MeetingTask;
use humhub\modules\tasks\models\forms\TaskForm;
use humhub\modules\tasks\widgets\TaskEditModal;


class TaskController extends ContentContainerController
{
    public function actionCreateTask($id, $meetingId)
    {
        $taskForm = new TaskForm(['submitUrl' => $this->contentContainer->createUrl('/meeting/task/create-task', ['id' => $id, 'meetingId' => $meetingId])]);
        $taskForm->createNew($this->contentContainer);

        if ($taskForm->load(Yii::$app->request->post()) && $taskForm->save()) {
            $meetingTask = new MeetingTask(['task_id' => $taskForm->task->id, 'meeting_item_id' => $id]);
            $meetingTask->save();

            return $this->htmlRedirect($this->contentContainer->createUrl('/meeting/index/view', ['id' => $meetingId]));
        }

        return $this->renderAjaxContent(TaskEditModal::widget(['taskForm' => $taskForm]));
    }
}