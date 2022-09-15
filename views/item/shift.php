<?php

use humhub\libs\Html;
use humhub\widgets\Button;
use humhub\widgets\ModalButton;
use humhub\widgets\ModalDialog;
use yii\bootstrap\ActiveForm;

/* @var $chooseModel \humhub\modules\meeting\models\ShiftMeetingChoose */

?>

<?php ModalDialog::begin(['header' => Yii::t('MeetingModule.views_item_shift', '<strong>Shift</strong> agenda item'),'size' => 'small']); ?>

    <div class="shift-menu modal-body">
        <?php if(!empty($chooseModel->getItems())) : ?>
            <?= Button::info(Yii::t('MeetingModule.views_item_shift', 'Chose upcoming meeting'))
                ->lg()->style('width:100%')->options(['data-shift-button' => '.shift-choose-meeting'])->loader(false); ?><br><br>
        <?php endif ?>

        <?= ModalButton::info(Yii::t('MeetingModule.views_item_shift', 'Create new meeting'))->lg()->style('width:100%')->load($createNewUrl) ?><br><br>
        <?= ModalButton::cancel()->lg()->style('width:100%'); ?>
    </div>

    <?php if(!empty($chooseModel->getItems())) : ?>
        <?php $form = ActiveForm::begin(); ?>
            <div class="shift-choose-meeting modal-body" style="display:none">
                    <?= $form->field($chooseModel, 'meetingId')->dropDownList($chooseModel->getItems(), ['data-ui-select2' => '', 'style' => 'width:100%'])?>
            </div>

            <div class="shift-choose-meeting modal-footer" style="display:none">
                <?= ModalButton::cancel()?>
                <?= ModalButton::submitModal($submitUrl); ?>
            </div>
        <?php ActiveForm::end(); ?>
    <?php endif; ?>

<?= Html::script(<<<JS
        $('[data-shift-button]').on('click', function() {
            $('.modal-body').hide();
            $('.modal-footer').hide();
            $($(this).data('shiftButton')).show();
        });
JS
) ?>
<?php ModalDialog::end(); ?>
