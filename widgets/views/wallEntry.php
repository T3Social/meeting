<?php
 /* @var $meeting \humhub\modules\meeting\models\Meeting */

use humhub\libs\Html;
use humhub\widgets\ModalButton;


?>
<div class="media meeting">
    <h5><b><?= Yii::t('MeetingModule.meeting', 'Begin')?>:</b> <?= $meeting->getFormattedDateTime() ?></h5>

    <?php if(!empty($meeting->location)) : ?>
        <h5><b><?= Yii::t('MeetingModule.meeting', 'Location')?>:</b>  <?= Html::encode($meeting->location) ?></h5>
    <?php endif ?>

    <?php if(!empty($meeting->room)) : ?>
        <h5><b><?= Yii::t('MeetingModule.meeting', 'Room')?>:</b>  <?= Html::encode($meeting->room) ?></h5>
    <?php endif ?>



    <?= ModalButton::primary(Yii::t('MeetingModule.widgets_views_wallentry', 'Open Meeting'))->close()->link($meeting->getUrl())->sm() ?>
</div>


