<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

use humhub\components\Migration;
use yii\db\Expression;

class m170505_085423_item_streamChannel extends Migration
{
    public function up()
    {
        $this->updateSilent('content', ['stream_channel' => new Expression("NULL")], ['object_model' => \humhub\modules\meeting\models\MeetingItem::class]);
    }

    public function down()
    {
        echo "m170505_085423_item_streamChannel cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
