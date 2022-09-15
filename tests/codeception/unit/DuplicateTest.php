<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

/**
 * Created by PhpStorm.
 * User: buddha
 * Date: 22.08.2017
 * Time: 21:37
 */

namespace humhub\modules\meeting\tests\codeception\unit;


use humhub\modules\meeting\models\forms\MeetingForm;
use humhub\modules\space\models\Space;
use tests\codeception\_support\HumHubDbTestCase;

class DuplicateTest extends HumHubDbTestCase
{
    public function _createTestMeetingForm()
    {
        $space1 = Space::findOne(['id' => 1]);

        $meetingForm = new MeetingForm();
        $meetingForm->createNew($space1);

        $this->assertTrue($meetingForm->load([
            'Meeting' => [
                'title' => 'Test title',
                'location' => 'TestLocation',
                'room' => 'TestRoom',
                'external_participants' => 'Some other dude'

            ],
            'MeetingForm' => [
                'startDate' => '7/15/17',
                'startTime' => '11:00 PM',
                'endTime' => '11:30 PM'
            ]
        ]));

        $this->assertTrue($meetingForm->save());

        return $meetingForm;
    }

    public function testDuplicateWithItems()
    {
        $this->becomeUser('Admin');
        $meeting = $this->_createTestMeetingForm()->meeting;

        $this->assertTrue($meeting->newItem('TestItem1', 30)->save());
        $this->assertTrue($meeting->newItem('TestItem2', 30)->save());

        $meetingForm = new MeetingForm(['duplicateId' => $meeting->id]);
        $meetingForm->createNew($meeting->content->container);
        $meetingForm->load([
            'Meeting' => [
                'title' => 'Duplicate title',
                'location' => 'TestLocation',
                'room' => 'TestRoom',
                'external_participants' => 'Some other dude'

            ],
            'MeetingForm' => [
                'startDate' => '8/15/17',
                'startTime' => '11:00 PM',
                'endTime' => '11:30 PM'
            ]
        ]);

        $this->assertTrue($meetingForm->save());

        $items = $meetingForm->meeting->getItems()->all();
        $this->assertEquals(2, count($items));
        $this->assertEquals('TestItem1', $items[0]->title );
        $this->assertEquals('TestItem2', $items[1]->title );

        $oldItems = $meeting->getItems()->all();
        $this->assertEquals(2, count($oldItems));
    }

    public function testDuplicateWithoutItems()
    {
        $this->becomeUser('Admin');
        $meeting = $this->_createTestMeetingForm()->meeting;

        $this->assertTrue($meeting->newItem('TestItem1', 30)->save());
        $this->assertTrue($meeting->newItem('TestItem2', 30)->save());

        $meetingForm = new MeetingForm(['duplicateId' => $meeting->id, 'duplicateItems' => 0]);
        $meetingForm->createNew($meeting->content->container);
        $meetingForm->load([
            'Meeting' => [
                'title' => 'Duplicate title',
                'location' => 'TestLocation',
                'room' => 'TestRoom',
                'external_participants' => 'Some other dude'

            ],
            'MeetingForm' => [
                'startDate' => '8/15/17',
                'startTime' => '11:00 PM',
                'endTime' => '11:30 PM'
            ]
        ]);

        $this->assertTrue($meetingForm->save());

        $items = $meetingForm->meeting->getItems()->all();
        $this->assertEquals(0, count($items));

        $oldItems = $meeting->getItems()->all();
        $this->assertEquals(2, count($oldItems));
    }
}