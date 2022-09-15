<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

namespace humhub\modules\meeting\widgets;

use humhub\modules\file\handler\FileHandlerCollection;
use Yii;

/**
 * Widget for rendering the menue buttons for the MeetingItem.
 * @author buddh4
 */
class MeetingItemMenu extends \yii\base\Widget
{

    /**
     * var MeetingItem
     */
    public $item;

    /**
     * @var \humhub\modules\content\components\ContentContainerActiveRecord Current content container.
     */
    public $contentContainer;

    /**
     * @var boolean Determines if the user has write permissions.
     */
    public $canEdit;


    /**
     * @inheritdoc
     */
    public function run()
    {
        if(!$this->canEdit) {
            return;
        }

        //$fileHandlerImport = FileHandlerCollection::getByType(FileHandlerCollection::TYPE_IMPORT);
        //$fileHandlerCreate = FileHandlerCollection::getByType(FileHandlerCollection::TYPE_CREATE);

        $deleteUrl = $this->contentContainer->createUrl('/meeting/item/delete', ['id' => $this->item->id]);
        $editUrl = $this->contentContainer->createUrl('/meeting/item/edit', ['id' => $this->item->id]);
        $shiftUrl = $this->contentContainer->createUrl('/meeting/item/shift', ['id' => $this->item->id]);
        $sendAsMessageUrl = $this->contentContainer->createUrl('/meeting/item/send', ['id' => $this->item->id]);
        //$uploadUrl = $this->contentContainer->createUrl('upload', ['openGalleryId' => $this->gallery->id]);

        $showMailIntegration = Yii::$app->getModule('meeting')->isMailIntegrationActive();

        return $this->render('meetingItemMenuDropdown', [
            'deleteUrl' => $deleteUrl,
            'editUrl' => $editUrl,
            'shiftUrl' => $shiftUrl,
            'sendUrl' => $sendAsMessageUrl,
            'showMailIntegration' => $showMailIntegration,
            'item' => $this->item
        ]);
    }

}
