<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

use humhub\modules\content\widgets\richtext\RichTextField;
use humhub\widgets\Button;
use yii\bootstrap\ActiveForm;
use humhub\widgets\ModalDialog;

$editUrl = $contentContainer->createUrl('/meeting/item/edit-protocol', ['meetingId' => $meetingId, 'id' => $item->id]);
?>

<?php ModalDialog::begin(['header' => Yii::t('MeetingModule.views_index_editMinutes', '<strong>Edit</strong> Note'), 'size' => 'large']) ?>
    <?php $form = ActiveForm::begin(); ?>
        <div class="modal-body">
            <?= $form->field($item, 'notes')->widget(RichTextField::class)->label(false); ?>
        </div>
        <div class="modal-footer">
            <?= Button::primary(Yii::t('base', 'Save'))->action('ui.modal.submit', $editUrl)?>

            <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('base', 'Cancel'); ?></button>
        </div>
    <?php ActiveForm::end(); ?>
<?php ModalDialog::end() ?>