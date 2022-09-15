<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

/**
 * Created by PhpStorm.
 * User: buddha
 * Date: 29.06.2017
 * Time: 17:52
 */

namespace humhub\modules\meeting\widgets;


use humhub\components\Widget;

class MeetingListEntry extends Widget
{
    public $meeting;
    public $canEdit;
    public $contentContainer;

    public function run()
    {
        return $this->render('meetingListEntry', [
            'meeting' => $this->meeting,
            'url' => $this->contentContainer->createUrl('/meeting/index/view', ['id' => $this->meeting->id]),
            'duplicateUrl' => $this->contentContainer->createUrl('/meeting/index/duplicate', ['id' => $this->meeting->id]),
            'editUrl' => $this->contentContainer->createUrl('/meeting/index/edit', ['id' => $this->meeting->id]),
            'canEdit' => $this->canEdit
        ]);
    }

}