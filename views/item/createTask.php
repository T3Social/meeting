<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

use humhub\libs\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

?>
<?php \humhub\widgets\ModalDialog::begin(['header' =>  Yii::t('MeetingModule.views_index_createTask', '<strong>Create</strong> new task')]) ?>

        <?php $form = ActiveForm::begin(); ?>

        <div class="modal-body">
            <?= $form->field($task, 'title')->textarea(['id' => 'itemTask', 'class' => 'form-control autosize', 'rows' => '1',
                'placeholder' => Yii::t('MeetingModule.views_index_createTask', 'What is to do?')]) ?>

            <div class="row">
                <div class="col-md-8">
                    <?= \humhub\modules\user\widgets\UserPickerField::widget([
                        'form' => $form,
                        'model' => $task,
                        'attribute' => 'assignedUserGuids',
                        'url' => $this->context->contentContainer->createUrl('/space/membership/search'),
                        'placeholder' => Yii::t('MeetingModule.views_index_createTask', 'Assign Users'),
                        'maxSelection' => 10
                    ]);  ?>

                </div>
                <div class="col-md-4">
                    <?= $form->field($task, 'deadline')->widget(DatePicker::className(), [
                            'dateFormat' => Yii::$app->params['formatter']['defaultDateFormat'],
                            'options' => ['class' => 'form-control', 'placeholder' => Yii::t('MeetingModule.views_index_createTask', 'Deadline')]
                    ])?>
                </div>
            </div>



            <div class="row">
                <div class="col-md-12">
                    <br>


                    <?php
                    echo \humhub\widgets\AjaxButton::widget([
                        'label' => Yii::t('MeetingModule.views_index_createTask', 'Save'),
                        'ajaxOptions' => [
                            'type' => 'POST',
                            'success' => 'function(html){ $("#globalModal").html(html); }',
                            'url' => $this->context->contentContainer->createUrl('/meeting/task/create-task', ['meetingId' => $meetingId, 'id' => $id]),
                        ],
                        'htmlOptions' => [
                            'class' => 'btn btn-primary'
                        ]
                    ]);
                    ?>

                    <button type="button" class="btn btn-primary"
                            data-dismiss="modal"><?= Yii::t('MeetingModule.views_index_createTask', 'Cancel'); ?></button>
                </div>
            </div>

        </div>

        <?php ActiveForm::end(); ?>

<?php \humhub\widgets\ModalDialog::end() ?>


<?= Html::script(<<<JS
    $(document).ready(function () {
        var myInterval = setInterval(function () {
            $('#itemTask').focus();
            clearInterval(myInterval);
        }, 100);
    });
JS
); ?>