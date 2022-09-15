<?php

use humhub\modules\space\widgets\Menu;
use humhub\modules\content\widgets\WallEntryAddons;
use yii\db\BaseActiveRecord;
use humhub\commands\IntegrityController;

return [
    'id' => 'meeting',
    'class' => 'humhub\modules\meeting\Module',
    'namespace' => 'humhub\modules\meeting',
    'events' => [
        ['class' => Menu::className(), 'event' => Menu::EVENT_INIT, 'callback' => ['humhub\modules\meeting\Events', 'onSpaceMenuInit']],
        ['class' => 'humhub\modules\tasks\models\Task', 'event' => BaseActiveRecord::EVENT_BEFORE_DELETE, 'callback' => ['humhub\modules\meeting\Events', 'onTaskDelete']],
        ['class' => WallEntryAddons::className(), 'event' => WallEntryAddons::EVENT_INIT, 'callback' => ['humhub\modules\meeting\Events', 'onTaskWallEntry']],
        ['class' => 'humhub\modules\calendar\interfaces\CalendarService', 'event' => 'getItemTypes', 'callback' => ['humhub\modules\meeting\Events', 'onGetCalendarItemTypes']],
        ['class' => IntegrityController::class, 'event' => IntegrityController::EVENT_ON_RUN, 'callback' => ['humhub\modules\meeting\Events', 'onIntegrityCheck']],
        ['class' => 'humhub\modules\calendar\interfaces\CalendarService', 'event' => 'findItems', 'callback' => ['humhub\modules\meeting\Events', 'onFindCalendarItems']],
    ]
];