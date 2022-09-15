<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */
?>

<?php

use humhub\widgets\Button;
use humhub\widgets\Link;
use humhub\widgets\ModalButton;


/* @var $editUrl string */
/* @var $shiftUrl string */
/* @var $deleteUrl string */
/* @var $sendUrl string */
/* @var $showMailIntegration bool */
?>

<div class="meeting-item-dropdown-menu pull-right" style="display:none;">
    <ul class="nav nav-pills preferences">
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-cog"></i>
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu pull-right">
                <li>
                    <?= ModalButton::asLink(Yii::t('MeetingModule.base', 'Edit'))->icon('fa-edit')->post($editUrl)->loader(false) ?>
                </li>
                <?php if(!is_string($item->begin)) : ?>
                    <li>
                        <?= Link::withAction(Yii::t('MeetingModule.base', 'Move down'),'moveDown')
                            ->cssClass('meeting-item-move-down')->icon('fa-level-down')->loader(false); ?>
                    </li>
                    <li>
                        <?= Link::withAction(Yii::t('MeetingModule.base', 'Move up'), 'moveUp')
                            ->cssClass('meeting-item-move-up')->icon('fa-level-up')->loader(false); ?>
                    </li>
                    <li>
                        <?= ModalButton::asLink(Yii::t('MeetingModule.base', 'Shift to other meeting'))
                            ->icon('fa-exchange')->load($shiftUrl)->loader(false); ?>
                    </li>
                    <?php if($showMailIntegration) :?>
                        <li>
                            <?= Button::asLink(Yii::t('MeetingModule.base', 'Send as message'))
                                ->icon('fa-envelope')->action('send', $sendUrl)->loader(false); ?>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <li>
                    <?= Button::none(Yii::t('base', 'Delete'))->action('ui.modal.post', $deleteUrl)->icon('fa-trash')
                        ->confirm(Yii::t('MeetingModule.views_index_editItem', '<strong>Confirm</strong> entry deletion'),
                            Yii::t('MeetingModule.views_index_editItem', 'Do you really want to delete this entry?'),
                            Yii::t('base', 'Delete'))->link(); ?>
                </li>
            </ul>
        </li>
    </ul>
</div>