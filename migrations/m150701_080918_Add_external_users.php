<?php

class m150701_080918_Add_external_users extends humhub\components\Migration
{

    public function up()
    {
        $this->addColumn('meeting', 'external_participants', 'TEXT NOT NULL');
        $this->addColumn('meeting_item', 'external_moderators', 'TEXT NOT NULL');
    }

    public function down()
    {
        echo "m150701_080918_Add_external_users does not support migration down.\n";
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
