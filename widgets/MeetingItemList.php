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
 * Date: 25.06.2017
 * Time: 22:57
 */

namespace humhub\modules\meeting\widgets;


use humhub\modules\meeting\models\Meeting;
use humhub\widgets\JsWidget;
use yii\helpers\Url;

class MeetingItemList extends JsWidget
{
    /**
     * @inheritdoc
     */
    public $jsWidget = 'meeting.ItemList';

    /**
     * @inheritdoc
     */
    public $id = 'meeting-items';

    /**
     * @inheritdoc
     */
    public $init = true;

    /**
     * @var Meeting
     */
    public $meeting;

    /**
     * @var Meeting
     */
    public $canEdit = false;

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('meetingItemList', [
            'options' => $this->getOptions(),
            'items' => $this->meeting->getItemsPopulated(),
            'meeting' => $this->meeting,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        $contentContainer = $this->meeting->content->container;
        return [
            'meeting-id' => $this->meeting->id,
            'drop-url' => $contentContainer->createUrl('/meeting/item/drop', ['meetingId' => $this->meeting->id]),
            'can-edit' => $this->canEdit
        ];
    }


}