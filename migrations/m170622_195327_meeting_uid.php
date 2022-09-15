<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

use yii\db\Migration;

class m170622_195327_meeting_uid extends Migration
{
    public function safeUp()
    {
        $this->addColumn('meeting', 'uid', $this->string(100));
    }

    public function safeDown()
    {
        echo "m170622_195326_addInterval cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170622_195326_addInterval cannot be reverted.\n";

        return false;
    }
    */
}
