<?php

use yii\db\Schema;
use humhub\components\Migration;
use humhub\modules\meeting\models\Meeting;
use humhub\modules\meeting\models\MeetingItem;

class m150728_130424_namespace extends Migration
{

    public function up()
    {
        $this->renameClass('Meeting', Meeting::className());
        $this->renameClass('MeetingItem', MeetingItem::className());
        $this->delete('notification', ['class' => 'MeetingInviteNotification']);
    }

    public function down()
    {
        echo "m150728_130424_namespace cannot be reverted.\n";

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
