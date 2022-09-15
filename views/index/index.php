<?php
\humhub\modules\meeting\assets\Assets::register($this);

use humhub\modules\meeting\widgets\MeetingListEntry;
use humhub\modules\meeting\widgets\MeetingListView;
use humhub\widgets\Button;
use humhub\widgets\ModalButton;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $canEdit boolean */
/* @var $contentContainer \humhub\modules\content\components\ContentContainerActiveRecord */
/* @var $pendingMeetings \humhub\modules\meeting\models\Meeting[] */
/* @var $meetingsPastProvider \yii\data\ActiveDataProvider */
/* @var $filter \humhub\modules\meeting\models\forms\MeetingFilter */

$createUrl = $contentContainer->createUrl('/meeting/index/edit');
$filterUrl = $contentContainer->createUrl('/meeting/index/filter-meetings');
$emptyText = ($canEdit) ? Yii::t('MeetingModule.views_index_index', "Start now, by creating a new meeting!")
    : Yii::t('MeetingModule.views_index_index', 'There are currently no upcoming meetings!.');

?>
<div class="panel panel-default meeting-overview">
    <div class="panel-heading">
        <i class="fa fa-calendar-o"></i> <?= Yii::t('MeetingModule.views_index_index', '<strong>Next</strong> meetings'); ?>
        <?php if ($canEdit) : ?>
            <?= ModalButton::success(Yii::t('MeetingModule.views_index_index', 'New meeting'))->post($createUrl)->sm()->icon('fa-plus')->right();?>
        <?php endif; ?>
    </div>

    <?php if (empty($pendingMeetings)) : ?>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12 text-center">
                    <?= $emptyText ?>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="panel-body">
            <ul class="media-list">
                <?php foreach ($pendingMeetings as $meeting): ?>
                    <li>
                        <?= MeetingListEntry::widget(['meeting' => $meeting, 'contentContainer' => $contentContainer, 'canEdit' => $canEdit]) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>

<div class="panel panel-default meeting-overview">
    <div class="panel-heading">
        <i class="fa fa-calendar-check-o"></i> <?= Yii::t('MeetingModule.views_index_index', '<strong>Meeting</strong> overview'); ?>
    </div>

    <div class="meeting-filter">
        <?php $form = ActiveForm::begin(['action' => $filterUrl,  'options' => [ 'data-ui-widget' => 'meeting.MeetingFilter', 'data-ui-init' => ''], 'enableClientValidation' => false]) ?>
                <?= $form->field($filter, 'title')->textInput(['id' => 'meetingfilter-title', 'placeholder' => Yii::t('MeetingModule.views_index_index', 'Filter meetings by title')])->label(false) ?>
        <div id="meeting-filter-loader" class="pull-right"></div>

                <div class="checkbox-filter">
                    <?= $form->field($filter, 'past')->checkbox(['style' => 'float:left']); ?>
                </div>
                <div class="checkbox-filter">
                    <?= $form->field($filter, 'participant')->checkbox(['style' => 'float:left']); ?>
                </div>
                <div class="checkbox-filter">
                    <?= $form->field($filter, 'own')->checkbox(['style' => 'float:left']); ?>
                </div>
        <?php ActiveForm::end() ?>
    </div>

    <div id="filter-meetings-list" class="panel-body">
        <?= MeetingListView::widget(['filter' => $filter, 'canEdit' => $canEdit]) ?>
    </div>
</div>
