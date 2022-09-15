<?php

use humhub\libs\Html;
use humhub\widgets\Button;

use humhub\widgets\ModalDialog;
use humhub\widgets\Link;
use humhub\widgets\ModalButton;
use humhub\widgets\ActiveForm;
use humhub\modules\user\widgets\UserPickerField;
use humhub\widgets\TimePicker;
use humhub\widgets\TimeZoneDropdownAddition;
use yii\jui\DatePicker;

/* @var $meetingForm \humhub\modules\meeting\models\forms\MeetingForm */

\humhub\modules\meeting\assets\Assets::register($this);

$meeting = $meetingForm->meeting;

?>

<?php ModalDialog::begin(['header' => $meetingForm->getTitle()]) ?>

<div class="modal-body meeting-edit-modal">
    <?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>
    <br>

    <?= $form->field($meeting, 'title')->textInput(['placeholder' => Yii::t('MeetingModule.views_index_edit', 'Title of your meeting')]); ?>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?= $form->field($meetingForm, 'startDate')->widget(DatePicker::class, ['dateFormat' => Yii::$app->formatter->dateInputFormat, 'clientOptions' => [],
                    'options' => ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('base', 'Date')]]); ?>
            </div>
        </div>
        <div class="col-md-3" style="padding-left:0px;">
            <?= $form->field($meetingForm, 'startTime')->widget(TimePicker::class)?>
        </div>
        <div class="col-md-3"  style="padding-left:0px;">
            <?= $form->field($meetingForm, 'endTime')->widget(TimePicker::class)?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
        </div>
        <div class="col-md-6">
            <?= $form->field($meetingForm, 'timeZone')->widget(TimeZoneDropdownAddition::class)->label(false)?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($meeting, 'location')->textInput(['placeholder' => Yii::t('MeetingModule.views_index_edit', 'Location')]); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($meeting, 'room')->textInput(['id' => 'meeting-end', 'placeholder' => Yii::t('MeetingModule.views_index_edit', 'Room')]); ?>
        </div>
    </div>

    <?= $form->field($meeting, 'inputParticipants')->widget(UserPickerField::class, [
            'id' => 'participantPicker',
            'selection' => $meeting->participantUsers,
            'url' => $meetingForm->getParticipantPickerUrl(),
            'placeholder' => Yii::t('MeetingModule.views_index_edit', 'Add participants')
    ]) ?>

    <?= Link::userPickerSelfSelect('#participantPicker'); ?>

    <?php if(!empty($meetingForm->duplicateId)) :?>
        <?= $form->field($meetingForm, 'duplicateId')->hiddenInput()->label(false) ?>
        <?= $form->field($meetingForm, 'duplicateItems')->checkbox() ?>
    <?php endif ?>

    <div class="row">
        <div class="col-md-12">
            <p>
                <a data-toggle="collapse" id="external-participants-link" href="#collapse-external-participants"
                   style="font-size: 11px;">
                    <i class="fa <?= empty($meeting->external_participants) ? "fa-caret-right" : "fa-caret-down" ?>"></i>
                    <?= Yii::t('MeetingModule.views_index_edit', 'External participants') ?>
                </a>
            </p>

            <div id="collapse-external-participants"
                 class="panel-collapse <?= empty($meeting->external_participants) ? "collapse" : "in" ?>">
                <?= $form->field($meeting, 'external_participants')->textInput(['id' => 'external_participants', 'placeholder' => Yii::t('MeetingModule.views_index_edit', 'Add external participants (free text)')]); ?>
                <br>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-<?= !$meeting->isNewRecord ? '8 text-left': '12 text-center' ?>">
            <?= ModalButton::cancel(); ?>
            <?= ModalButton::submitModal($meetingForm->getSubmitUrl())?>
        </div>
        <?php if (!$meeting->isNewRecord): ?>
            <div class="col-md-4 text-right">
                    <?= Button::danger(Yii::t('MeetingModule.base', 'Delete'))->confirm(
                        Yii::t('MeetingModule.views_index_edit', '<strong>Confirm</strong> meeting deletion'),
                        Yii::t('MeetingModule.views_index_edit', 'Do you really want to delete this meeting?'),
                        Yii::t('MeetingModule.base', 'Delete'))->action('ui.modal.post', $meetingForm->getDeleteUrl()); ?>
            </div>
        <?php endif; ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php ModalDialog::end() ?>

<?= Html::script(<<<JS
    $('#collapse-external-participants').on('show.bs.collapse', function () {
        $('#external-participants-link i').switchClass('fa-caret-right', 'fa-caret-down', 0);
    }).on('hide.bs.collapse', function () {
        $('#external-participants-link i').switchClass('fa-caret-down', 'fa-caret-right', 0);
    }).on('shown.bs.collapse', function () {
        $('#external_participants').focus();
    });
JS
); ?>