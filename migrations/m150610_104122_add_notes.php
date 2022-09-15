<?php

class m150610_104122_add_notes extends humhub\components\Migration
{

    public function up()
    {
        $this->addColumn('meeting_item', 'notes', 'TEXT NOT NULL');
    }

    public function down()
    {
        echo "m150610_104122_add_notes does not support migration down.\n";
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
