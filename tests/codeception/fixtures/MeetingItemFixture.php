<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

namespace humhub\modules\calendar\tests\codeception\fixtures;

use yii\test\ActiveFixture;

class MeetingItemFixture extends ActiveFixture
{
    public $modelClass = 'humhub\modules\meeting\models\MeetingItem';
    public $dataFile = '@meeting/tests/codeception/fixtures/data/meetingItem.php';
   
}
