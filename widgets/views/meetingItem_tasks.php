<?php
/* @var $this \yii\web\View */
/* @var $item \humhub\modules\meeting\models\MeetingItem */
/* @var $meeting \humhub\modules\meeting\models\Meeting */
/* @var $canEdit boolean */
/* @var $contentContainer \humhub\modules\content\components\ContentContainerActiveRecord */

use humhub\libs\Html;
use humhub\modules\user\widgets\Image;
use humhub\widgets\ModalButton;

$editTaskLink = $contentContainer->createUrl('/meeting/task/create-task', ['id' => $item->id, 'meetingId' => $meeting->id]);
?>

<?php if ($contentContainer->moduleManager->isEnabled('tasks')) : ?>
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-11">
            <div class="meeting-item-option">
                <div class="row">
                    <div class="col-md-2">
                        <strong><?= Yii::t('MeetingModule.views_index_index', 'Tasks'); ?></strong>
                    </div>
                    <div class="col-md-10">

                        <?php foreach ($item->meetingTasks as $task): ?>
                            <?php /* @var $task \humhub\modules\meeting\models\MeetingTask */ ?>

                            <?php if(!$task->task) {continue;} // Just to get sure there are no integrity issues ?>

                            <a href="<?= $task->getUrl() ?>">
                                <div class="meeting-task clearfix <?= $task->isCompleted() ? "task-finished" : '' ?>">
                                    <i class="fa <?= $task->isCompleted() ? "fa-check-square-o" : "fa-square-o" ?>"></i>
                                    <span class="task-title"><?= Html::encode($task->getTitle()) ?></span>
                                    <?php if ($task->hasScheduling()) : ?>
                                        <span class="<?= ($task->isOverdue()) ? 'label label-danger' : 'label label-default' ?>" style="<?= ($task->isCompleted()) ? 'opacity: 0.3;' : '' ?>">
                                            <?= Yii::$app->formatter->asDate($task->getEndDate()) ?>
                                        </span>
                                    <?php endif ?>

                                    <div class="pull-right">
                                        <?php foreach ($task->getAssignedUsers() as $user): ?>
                                            <a href="<?= $user->getUrl(); ?>" id="user_<?= $task->id; ?>">
                                                <?= Image::widget(['user' => $user, 'width' => 24,'showTooltip' => true]) ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </a>

                        <?php endforeach; ?>

                        <span class="meeting-item-menu">
                            <?php  if ($canEdit && $contentContainer->can(\humhub\modules\tasks\permissions\CreateTask::class)) : ?>
                                <?= ModalButton::asLink(Yii::t('MeetingModule.views_index_index', 'Add a task'))->load($editTaskLink)->icon('fa-plus')->loader(false); ?>
                            <?php else: ?>
                                <b>-</b>
                            <?php endif ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php endif; ?>