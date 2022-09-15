<?php

class uninstall extends humhub\components\Migration
{

    public function up()
    {
        $this->dropTable('meeting');
        $this->dropTable('meeting_item');
        $this->dropTable('meeting_item_moderator');
        $this->dropTable('meeting_participant');
        $this->dropTable('meeting_task');
    }

    public function down()
    {
        echo "m150629_144032_uninstall does not support migration down.\n";
        return false;
    }

}
