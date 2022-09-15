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
 * Date: 01.07.2017
 * Time: 19:24
 */

namespace humhub\modules\meeting\widgets;


use humhub\components\Widget;
use humhub\modules\meeting\models\Meeting;

class MeetingBadge extends Widget
{
    /**
     * @var Meeting
     */
    public $meeting;

    /**
     * @var Meeting
     */
    public $right;

    public function run()
    {
        return $this->render('meetingBadge', [
            'meeting' => $this->meeting,
            'right' => $this->right
        ]);
    }

}