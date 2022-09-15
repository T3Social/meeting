<?php

/* @var $this \humhub\modules\ui\view\components\View */
/* @var $meeting \humhub\modules\meeting\models\Meeting */
/* @var $contentContainer \humhub\modules\content\components\ContentContainerActiveRecord */

use humhub\modules\meeting\assets\Assets;
use humhub\modules\meeting\widgets\MeetingItemList;
use humhub\widgets\ModalButton;
use humhub\libs\Html;
use humhub\modules\comment\widgets\CommentLink;
use humhub\modules\like\widgets\LikeLink;
use \humhub\modules\comment\widgets\Comments;

Assets::register($this);

$canEdit = $meeting->content->canEdit();
$createItemUrl = $contentContainer->createUrl('/meeting/item/edit', ['meetingId' => $meeting->id]);
$printUrl = $contentContainer->createUrl('print', ['id' => $meeting->id]);
$shareLink = $contentContainer->createUrl('share', ['id' => $meeting->id]);

$this->registerJsConfig('meeting', [
    'text' => [
        'success.notification' => Yii::t('MeetingModule.views_index_meeting', 'Participants have been notified')
    ]
]);

$participantStyle = empty($meeting->location) ? 'display:inline-block;' :  'display:inline-block;padding-right:10px;border-right:2px solid '. $this->theme->variable('default');
$locationStyle = ($meeting->hasParticipants()) ? 'display:inline-block;padding-left:10px;vertical-align:top;' : 'display:inline-block;';

?>
<div id="meeting-container" class="panel panel-default meeting-details">
    <?= $this->render('@meeting/views/index/meeting_header', [
        'canEdit' => $canEdit,
        'contentContainer' => $contentContainer,
        'meeting' => $meeting
    ]); ?>
    <div class="panel-body">

        <?php if($meeting->hasParticipants() || !empty($meeting->location)): ?>
        <div>
            <?php if ($meeting->hasParticipants()) : ?>
                <div style="<?= $participantStyle ?>">
                    <em><strong><?= Yii::t('MeetingModule.views_index_index', 'Participants') ?>:</strong></em><br>
                    <?php foreach ($meeting->participantUsers as $user) : ?>
                        <a href="<?= $user->getUrl(); ?>">
                            <?= \humhub\modules\user\widgets\Image::widget(['user' => $user, 'width' => 24, 'showTooltip' => true]) ?>
                        </a>
                    <?php endforeach; ?>
                    <?php if (!empty($meeting->external_participants)) : ?>
                        <?= !empty($meeting->participantUsers) ? '<i class="fa fa-plus-circle"></i>' : ''?>
                        <i>
                            <?= Html::encode($meeting->external_participants); ?>
                        </i>

                    <?php endif; ?>
                </div>
            <?php endif ?>

            <?php if (!empty($meeting->location)) : ?>
                <div style="<?= $locationStyle ?>">
                    <em><strong><?= Yii::t('MeetingModule.views_index_index', 'Location') ?>:</strong></em><br>
                    <?= Html::encode($meeting->location) ?>
                    <?php if ($meeting->room != null) : ?>
                        (<?= Html::encode($meeting->room) ?>)
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <hr>
        </div>
        <?php endif; ?>
        <?= MeetingItemList::widget(['meeting' => $meeting, 'canEdit' => $canEdit]) ?>

        <?php if ($canEdit): ?>

            <div class="row">
                <div class="col-md-12 text-center">
                    <?php if (count($meeting->items) == 0) : ?>
                        <?= Yii::t('MeetingModule.views_index_index', 'Create your first agenda entry by clicking the following button.'); ?>
                        <br>
                    <?php endif; ?>
                    <br>
                    <?= ModalButton::info(Yii::t('MeetingModule.views_index_index', 'New agenda entry'))->id('meeting-agenda-create')->load($createItemUrl)->lg()->icon('fa-plus')?>
                    <br><br><br>
                </div>
            </div>

        <?php else: ?>
            <br><br>
        <?php endif; ?>

        <?php if ($meeting->content->canView()) : // If the meeting is private and non space members are invited the meeting is visible, but not commentable etc. ?>
            <hr style="margin-bottom: 0;">

            <div class="row">
                <div class="col-md-12">
                    <div class="wall-entry">
                        <div class="wall-entry-controls">
                            <?= CommentLink::widget(['object' => $meeting]); ?>
                            · <?= LikeLink::widget(['object' => $meeting]); ?>
                        </div>
                    </div>
                    <?= Comments::widget(['object' => $meeting]); ?>
                </div>
            </div>
        <?php else: ?>
            <br>
        <?php endif; ?>
    </div>
</div>
