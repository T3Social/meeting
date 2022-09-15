<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

namespace humhub\modules\meeting\widgets;

use humhub\modules\file\handler\FileHandlerCollection;
use humhub\modules\meeting\models\Meeting;

/**
 * Widget for rendering the menue buttons for a Meeting.
 * @author buddh4
 */
class MeetingMenu extends \yii\base\Widget
{

    /**
     * @var Meeting
     */
    public $meeting;

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

        $deleteUrl = $this->contentContainer->createUrl('/meeting/index/delete', ['id' => $this->meeting->id]);
        $editUrl = $this->contentContainer->createUrl('/meeting/index/edit', ['id' => $this->meeting->id]);
        $printUrl = $this->contentContainer->createUrl('/meeting/index/print', ['id' => $this->meeting->id]);
        $shareUrl = $this->contentContainer->createUrl('/meeting/index/share', ['id' => $this->meeting->id]);
        $duplicateUrl = $this->contentContainer->createUrl('/meeting/index/duplicate', ['id' => $this->meeting->id]);

        return $this->render('meetingMenuDropdown', [
                    'deleteUrl' => $deleteUrl,
                    'editUrl' => $editUrl,
                    'printUrl' => $printUrl,
                    'shareUrl' => $shareUrl,
                    'canEdit' => $this->canEdit,
                    'duplicateUrl' => $duplicateUrl
        ]);
    }

}
