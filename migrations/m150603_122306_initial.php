<?php

class m150603_122306_initial extends humhub\components\Migration
{

    public function up()
    {
        $this->createTable('meeting', array(
            'id' => 'pk',
            'title' => 'varchar(255) NOT NULL',
            'date' => 'DATE NOT NULL',
            'begin' => 'TIME NOT NULL',
            'end' => 'TIME NOT NULL',
            'location' => 'varchar(255) NOT NULL',
            'room' => 'varchar(255) NOT NULL',
                ), '');

        $this->createTable('meeting_participant', array(
            'id' => 'pk',
            'meeting_id' => 'int(11) NOT NULL',
            'user_id' => 'int(11) NOT NULL',
            'name' => 'varchar(255) NOT NULL',
                ), '');

        $this->createTable('meeting_item', array(
            'id' => 'pk',
            'meeting_id' => 'int(11) NOT NULL',
            'begin' => 'TIME NOT NULL',
            'end' => 'TIME NOT NULL',
            'title' => 'VARCHAR(255) NOT NULL',
            'description' => 'TEXT NOT NULL',
                #'moderator' => 'varchar(255) NOT NULL',
                ), '');

        $this->createTable('meeting_item_moderator', array(
            'id' => 'pk',
            'meeting_item_id' => 'int(11) NOT NULL',
            'user_id' => 'int(11) NOT NULL',
            'name' => 'varchar(255) NOT NULL',
                ), '');
    }

    public function down()
    {
        echo "m150603_122306_initial does not support migration down.\n";
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
