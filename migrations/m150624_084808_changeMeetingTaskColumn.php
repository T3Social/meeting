<?php

class m150624_084808_changeMeetingTaskColumn extends humhub\components\Migration
{

    public function up()
    {
        $this->renameColumn('meeting_task', 'meeting_id', 'meeting_item_id');
    }

    public function down()
    {
        echo "m150624_084808_changeMeetingTaskColumn does not support migration down.\n";
        return false;
    }

    /*
      // Use safeUp/safeDown to do migration with transaction
      public function safeUp()
      {
      }

      public function safeDown()
      {
      }
     */
}
