<?php

use yii\db\Migration;

class m160517_194539_fixed_field_allow_null extends Migration
{
    public function up()
    {
        $this->alterColumn('meeting', 'location', 'varchar(255) NULL');
        $this->alterColumn('meeting', 'room', 'varchar(255) NULL');
        $this->alterColumn('meeting', 'external_participants', 'TEXT NULL');
        
        $this->alterColumn('meeting_participant', 'name', 'varchar(255) NULL');
        
        $this->alterColumn('meeting_item', 'begin', 'TIME NULL');
        $this->alterColumn('meeting_item', 'end', 'TIME NULL');
        $this->alterColumn('meeting_item', 'description', 'TEXT NULL');
        $this->alterColumn('meeting_item', 'external_moderators', 'TEXT NULL');
        $this->alterColumn('meeting_item', 'notes', 'TEXT NULL');
        
        $this->alterColumn('meeting_item_moderator', 'name', 'varchar(255) NULL');
    }


    public function down()
    {
        echo "m160517_194539_fixed_field_allow_null cannot be reverted.\n";

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
