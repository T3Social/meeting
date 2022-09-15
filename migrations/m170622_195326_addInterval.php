<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

use yii\db\Migration;

class m170622_195326_addInterval extends Migration
{
    public function safeUp()
    {
        $this->addColumn('meeting_item', 'duration', 'SMALLINT DEFAULT NULL');
        $this->addColumn('meeting_item', 'sort_order', "int(11) NOT NULL DEFAULT '1'");
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
