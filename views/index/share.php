<?php

use humhub\widgets\Button;
use humhub\widgets\Link;
use humhub\widgets\ModalButton;
use yii\helpers\Html;

$notificationUrl = $contentContainer->createUrl('/meeting/index/send-invite-notifications', ['id' => $meeting->id]);
$sharePublicUrl = $contentContainer->createUrl('get-ics', ['id' => $meeting->id, 'type' => 'public']);
$sharePrivateUrl = $contentContainer->createUrl('get-ics', ['id' => $meeting->id, 'type' => 'private']);

?>
<div class="modal-dialog modal-dialog-normal animated fadeIn meeting-item-modal">
    <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"
                id="myModalLabel"><?= Yii::t('MeetingModule.views_index_share', '<strong>Share</strong> meeting'); ?></h4>
        </div>


        <div class="modal-body">
            <br>

            <div class="row">
                <div class="col-md-8">
                    <strong><?= Yii::t('MeetingModule.views_index_share', 'Add to your personal calendar'); ?></strong><br>
                    <?= Yii::t('MeetingModule.views_index_share', 'This will create an ICS file, which adds this meeting only to your private calendar.'); ?>
                </div>
                <div class="col-md-4 text-right">
                    <?= ModalButton::info(Yii::t('MeetingModule.views_index_share', 'Export ICS'))->link($sharePrivateUrl, false)->close(); ?>
                </div>
            </div>
            <?php if ($canEdit): ?>
                <hr>
                <div class="row">
                    <div class="col-md-8">
                        <strong><?= Yii::t('MeetingModule.views_index_share', 'Add to your calendar and invite participants'); ?></strong><br>
                        <?= Yii::t('MeetingModule.views_index_share', 'This will create an ICS file, which adds this meeting to your personal calendar, invite all other participants by email and waits for their response.'); ?>
                    </div>
                    <div class="col-md-4 text-right">
                        <?= ModalButton::info(Yii::t('MeetingModule.views_index_share', 'Export ICS'))->link($sharePublicUrl, false)->close(); ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-8">
                        <strong><?= Yii::t('MeetingModule.views_index_share', 'Send notifications to all participants'); ?></strong><br>
                        <?= Yii::t('MeetingModule.views_index_share', 'Sends internal notifications to all participants of the meeting.'); ?>
                    </div>
                    <div class="col-md-4 text-right">
                        <?= Button::info(Yii::t('MeetingModule.views_index_share', 'Send now'))->action('meeting.sendNotification',  $notificationUrl)?>
                    </div>
                </div>
            <?php endif; ?>
            <br><br>

            <div class="row">
                <div class="col-md-12 text-center">
                    <?= ModalButton::cancel() ?>
                </div>
            </div>
        </div>

    </div>
</div>

