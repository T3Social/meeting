<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */
use humhub\modules\meeting\widgets\MeetingListEntry;

?>
<?= MeetingListEntry::widget(['meeting' => $model, 'contentContainer' => $contentContainer, 'canEdit' => $canEdit])?>

