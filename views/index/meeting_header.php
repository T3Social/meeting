<?php
use humhub\libs\Html;
use humhub\modules\meeting\widgets\MeetingBadge;
use humhub\modules\meeting\widgets\MeetingMenu;
use humhub\widgets\Button;
use humhub\widgets\TimeAgo;

/* @var $contentContainer \humhub\modules\content\components\ContentContainerActiveRecord */
/* @var $meeting \humhub\modules\meeting\models\Meeting */
/* @var $canEdit boolean */

$editUrl = $contentContainer->createUrl('edit', ['id' => $meeting->id]);
$icon = !$meeting->isToday() && $meeting->isPast() ? 'fa-calendar-check-o' : 'fa-calendar-o';
$backUrl = $this->context->contentContainer->createUrl('/meeting/index');

?>
<div class="panel-heading clearfix">
    <div>
        <strong><i class="fa <?= $icon ?>"></i> <?= Html::encode($meeting->title); ?></strong>
    </div>

    <?= MeetingMenu::widget(['meeting' => $meeting,
        'canEdit' => $canEdit,
        'contentContainer' => $contentContainer]); ?>

    <div class="row clearfix">
        <div class="col-sm-12 media">

            <h2 style="margin:5px 0 0 0;">
                <?= $meeting->getFormattedDateTime(); ?>
            </h2>
                <span class="author">
                    <?= Html::containerLink($meeting->content->createdBy); ?>
                </span>
            <?php if ( $meeting->content->updated_at !== null) : ?>
                &middot <span class="tt updated" title="<?= Yii::$app->formatter->asDateTime($meeting->content->updated_at); ?>"><?= Yii::t('ContentModule.base', 'Updated'); ?></span>
            <?php endif; ?>


            <?php $badge = MeetingBadge::widget(['meeting' => $meeting])?>
            <?= (!empty($badge)) ? '<br>'.$badge : '' ?>

            <?php if($meeting->content->isPublic()) : ?>
                <span class="label label-info"><?= Yii::t('base', 'Public');?></span>
            <?php endif; ?>

            <?= Button::back($backUrl,  Yii::t('MeetingModule.base', 'Back to overview'))->sm()->loader(true); ?>
        </div>
    </div>
</div>