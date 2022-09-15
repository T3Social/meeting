<?php
/* @var $this \yii\web\View */
/* @var $meeting \humhub\modules\meeting\models\Meeting */
/* @var $item \humhub\modules\meeting\models\MeetingItem */
/* @var $contentContainer \humhub\modules\content\components\ContentContainerActiveRecord */
/* @var $canEdit boolean */
/* @var $editUrl string */
/* @var $editMinutesUrl string */

use humhub\libs\Html;
use humhub\modules\content\widgets\richtext\RichText;
use humhub\modules\meeting\widgets\MeetingItemMenu;
use humhub\widgets\ModalButton;

?>
<?= Html::beginTag('li', $options) ?>
    <div class="meeting-item" id="item-<?= $item->id; ?>">

        <div class="row">
            <div class="col-md-1 agenda-time-line">
                <div class="agenda-point backgroundInfo"></div>
            </div>
            <div class="col-md-11">

                <div class="meeting-item-content">

                    <div class="meeting-item-head">
                        <span class="meeting-drag-icon tt" title="<?= Yii::t('MeetingModule.views_index_index', 'Drag entry')?>" style="display:none">
                            <i class="fa fa-arrows"></i>&nbsp;
                        </span>

                        <?php if ($item->begin != "00:00" && $item->end != "00:00") : ?>
                            <h1 class="meeting-item-time-range colorInfo"><?= Html::encode($item->getTimeRangeText()); ?></h1>
                        <?php endif; ?>
                        <?php if ($canEdit && is_string($item->begin)) : ?>
                            <span class="help-block legacyFlag">
                                <?= Yii::t('MeetingModule.views_index_index', 'Note: This agenda entry still uses a legacy time format, please save all legacy items in order to use new features.'); ?>
                            </span>
                        <?php endif ?>
                    </div>


                    <?= MeetingItemMenu::widget(['contentContainer' => $contentContainer, 'item' => $item, 'canEdit' => $canEdit]) ?>

                    <h1 class="meeting-item-title"><?= Html::encode($item->title); ?></h1>

                    <?= RichText::output($item->description); ?>

                    <?php if (count($item->moderators) != 0 || $item->external_moderators != null) : ?>
                        <div class="row meeting-item-option">
                            <div class="col-md-2">
                                <strong><?= Yii::t('MeetingModule.views_index_index', 'Moderators'); ?></strong>
                            </div>
                            <div class="col-md-10">
                                <?php foreach ($item->moderators as $moderator) : ?>
                                    <?php $user = $moderator->user; ?>
                                    <?php if ($user): ?>
                                        <a href="<?= $user->getUrl(); ?>">
                                            <?= \humhub\modules\user\widgets\Image::widget(['user' => $user, 'width' => 24]); ?>
                                            <?= Html::encode($user->displayName); ?>
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?php if ($item->external_moderators != null) : ?>
                                    <?php if (count($item->moderators) != 0) : ?>
                                        <?= ", " ?>
                                    <?php endif; ?>
                                    <?= $item->external_moderators; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-11">
                <div class="meeting-item-option item-protocol">
                    <div class="row" style="padding: 4px 0;">

                        <div class="col-md-2">
                            <strong><?= Yii::t('MeetingModule.views_index_index', 'Protocol'); ?></strong>
                        </div>
                        <div class="col-md-10">
                            <?php if ($item->notes) : ?>
                                <div class="notes-content">
                                    <?= RichText::output($item->notes); ?>
                                </div>

                                <span class="meeting-item-menu">
                                    <?= ModalButton::asLink(Yii::t('MeetingModule.views_index_index', 'Edit a protocol'))
                                        ->load($editProtocolUrl)->icon('fa-pencil')->loader(false)->visible($canEdit); ?>
                                </span>
                            <?php else: ?>
                                <span class="meeting-item-menu">
                                    <?php if ($canEdit) : ?>
                                        <?= ModalButton::asLink(Yii::t('MeetingModule.views_index_index', 'Add a protocol'))
                                            ->load($editProtocolUrl)->icon('fa-plus')->loader(false); ?>
                                    <?php else: ?>
                                        <b>-</b>
                                    <?php endif; ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <?= $this->render('@meeting/widgets/views/meetingItem_tasks', [
            'item' => $item,
            'meeting' => $meeting,
            'contentContainer' => $contentContainer,
            'canEdit' => $canEdit]);
        ?>


        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-11">
                <br>
                <hr class="buttom-line">
                <br>
            </div>
        </div>
    </div>
<?= Html::endTag('li') ?>