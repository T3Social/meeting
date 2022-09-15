<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

namespace humhub\modules\meeting\tests\codeception\unit;

use DateTime;
use humhub\modules\content\models\Content;
use humhub\modules\meeting\models\Meeting;
use tests\codeception\_support\HumHubDbTestCase;
use humhub\modules\space\models\Space;


class MeetingTest extends HumHubDbTestCase
{

    /**
     * Test find dates by open range query.
     */
    public function testItemCreation()
    {
        $this->becomeUser('Admin');

        $meeting = $this->_createTestMeeting();

        $meeting->newItem('TestItem1')->save();
        $meeting->newItem('TestItem2')->save();
        $meeting->newItem('TestItem3')->save();

        $meeting->refresh();
        $items = $meeting->items;

        $this->assertEquals(3, count($items));
        $this->assertEquals($items[0]->title, 'TestItem1');
        $this->assertEquals($items[1]->title, 'TestItem2');
        $this->assertEquals($items[2]->title, 'TestItem3');

        $this->assertEquals($items[0]->sort_order, 0);
        $this->assertEquals($items[1]->sort_order, 1);
        $this->assertEquals($items[2]->sort_order, 2);


        // Move down
        $meeting->moveItemIndex($items[0]->id, 2);
        $items = $meeting->items;

        $this->assertEquals('TestItem2', $items[0]->title);
        $this->assertEquals('TestItem3', $items[1]->title);
        $this->assertEquals('TestItem1', $items[2]->title);

        $this->assertEquals(0, $items[0]->sort_order);
        $this->assertEquals(1, $items[1]->sort_order);
        $this->assertEquals(2, $items[2]->sort_order);

        // Move up
        $meeting->moveItemIndex($items[1]->id, 0);
        $items = $meeting->items;

        $this->assertEquals('TestItem3', $items[0]->title);
        $this->assertEquals('TestItem2', $items[1]->title);
        $this->assertEquals('TestItem1', $items[2]->title);

        $this->assertEquals(0, $items[0]->sort_order);
        $this->assertEquals(1, $items[1]->sort_order);
        $this->assertEquals(2, $items[2]->sort_order);

        // Add new item
        $meeting->newItem('TestItem4')->save();
        $meeting->refresh();

        $items = $meeting->items;

        $this->assertEquals('TestItem3', $items[0]->title);
        $this->assertEquals('TestItem2', $items[1]->title);
        $this->assertEquals('TestItem1', $items[2]->title);
        $this->assertEquals('TestItem4', $items[3]->title);

        $this->assertEquals(0, $items[0]->sort_order);
        $this->assertEquals(1, $items[1]->sort_order);
        $this->assertEquals(2, $items[2]->sort_order);
        $this->assertEquals(3, $items[3]->sort_order);

        $meeting->moveItemIndex($items[0]->id, 3);

        $items = $meeting->items;

        $this->assertEquals('TestItem2', $items[0]->title);
        $this->assertEquals('TestItem1', $items[1]->title);
        $this->assertEquals('TestItem4', $items[2]->title);
        $this->assertEquals('TestItem3', $items[3]->title);

        $this->assertEquals(0, $items[0]->sort_order);
        $this->assertEquals(1, $items[1]->sort_order);
        $this->assertEquals(2, $items[2]->sort_order);
        $this->assertEquals(3, $items[3]->sort_order);

        $items = $meeting->items;

        $meeting->moveItemIndex($items[2]->id, 0);

        $items = $meeting->items;

        $this->assertEquals('TestItem4', $items[0]->title);
        $this->assertEquals('TestItem2', $items[1]->title);
        $this->assertEquals('TestItem1', $items[2]->title);
        $this->assertEquals('TestItem3', $items[3]->title);

        $this->assertEquals(0, $items[0]->sort_order);
        $this->assertEquals(1, $items[1]->sort_order);
        $this->assertEquals(2, $items[2]->sort_order);
        $this->assertEquals(3, $items[3]->sort_order);
    }

    public function testItemShift()
    {
        $this->becomeUser('Admin');

        $meeting = $this->_createTestMeeting();
        $meetingItem = $meeting->newItem('TestItemToShift');
        $this->assertTrue($meetingItem->save());
        $this->assertEquals(1, count($meeting->getItems()->all()));

        $meeting2 = $this->_createTestMeeting('Test Meeting 2');
        $meeting2->shiftItem($meetingItem->id);

        $this->assertEquals(0, count($meeting->getItems()->all()));
        $this->assertEquals(1, count($meeting2->items));
        $this->assertEquals('TestItemToShift',$meeting2->items[0]->title);
    }

    public function _createTestMeeting($title = 'Test Meeting')
    {
        $s1 = Space::findOne(['id' => 1]);
        $meeting = new Meeting($s1, null, [
            'title' => $title,
            'date' => '2017-07-18 12:00:00',
            'begin' => '12:00:00',
            'end' => '15:30:00',
        ]);

        $this->assertTrue($meeting->save());
        return $meeting;
    }
}
