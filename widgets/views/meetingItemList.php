<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

/* @var $this \yii\web\View */
/* @var $meeting \humhub\modules\meeting\models\Meeting */
/* @var $items \humhub\modules\meeting\models\MeetingItem[] */
/* @var $options array */

use humhub\libs\Html;
use humhub\modules\meeting\widgets\MeetingItemWidget;

?>
<div class="<?= (count($items)) ? "meeting-item-container" : '' ?>">
    <?= Html::beginTag('ul', $options) ?>
        <?php foreach ($items as $item): ?>
            <?= MeetingItemWidget::widget(['item' => $item, 'meeting' => $meeting]); ?>
        <?php endforeach; ?>
    <?= Html::endTag('ul') ?>
</div>
