<?php
use humhub\libs\Html;

/* @var $meeting \humhub\modules\meeting\models\Meeting */
/* @var $url string */
/* @var $canEdit boolean */
/* @var $duplicateUrl string */
?>

<a href="<?= $url ?>">
    <div class="media meeting">

        <div class="media-body">
            <?= \humhub\modules\meeting\widgets\MeetingBadge::widget(['meeting' => $meeting, 'right' => true])?>

            <h4 class="media-heading"><?= Html::encode($meeting->title); ?></h4>
            <h5>
                <?= Yii::$app->formatter->asDate($meeting->date); ?>
                <?= Yii::t('MeetingModule.views_index_index', 'at') ?>
                <?= Yii::$app->formatter->asTime(new DateTime($meeting->begin, new DateTimeZone(Yii::$app->timeZone)), 'short'); ?>
                - <?= Yii::$app->formatter->asTime(new DateTime($meeting->end, new DateTimeZone(Yii::$app->timeZone)), 'short'); ?>
                <?php if ($meeting->location) : ?>
                    , <?= Html::encode($meeting->location) ?>
                <?php endif; ?>
                <?php if ($meeting->room) : ?>
                    (<?= Html::encode($meeting->room) ?>)
                <?php endif; ?>
            <?= \humhub\widgets\Button::primary()
                ->options(['class' => 'tt', 'title' => Yii::t('MeetingModule.views_index_index', 'Edit'), 'style' => 'margin-left:2px']
                )->icon('fa-pencil')->right()->xs()->action('ui.modal.load', $editUrl)->loader(false)->visible($canEdit) ?>
            <?= \humhub\widgets\Button::defaultType()
                ->options(['class' => 'tt', 'title' => Yii::t('MeetingModule.views_index_index', 'Duplicate')]
                )->icon('fa-clone')->right()->xs()->action('ui.modal.load', $duplicateUrl)->loader(false)->visible($canEdit) ?>
            </h5>
        </div>

    </div>
</a>