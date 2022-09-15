<?php

class m150623_145312_createMeetingTasks extends humhub\components\Migration
{

    public function up()
    {

        $this->createTable('meeting_task', array(
            'id' => 'pk',
            'task_id' => 'int(11) NOT NULL',
            'meeting_id' => 'int(11) NOT NULL',
                ), '');
    }

    public function down()
    {
        echo "m150623_145312_createMeetingTasks does not support migration down.\n";
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
