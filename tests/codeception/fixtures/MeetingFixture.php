<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

namespace humhub\modules\meeting\tests\codeception\fixtures;

use humhub\modules\calendar\tests\codeception\fixtures\MeetingItemFixture;
use yii\test\ActiveFixture;

class MeetingFixture extends ActiveFixture
{
    public $modelClass = 'humhub\modules\meeting\models\Meeting';
    public $dataFile = '@meeting/tests/codeception/fixtures/data/meeting.php';
    
     public $depends = [
        MeetingItemFixture::class
    ];
}
