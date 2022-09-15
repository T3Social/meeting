<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

namespace humhub\modules\meeting\tests\codeception\unit;

use humhub\modules\meeting\models\forms\MeetingForm;
use humhub\modules\meeting\models\Meeting;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;
use tests\codeception\_support\HumHubDbTestCase;
use Yii;

/**
 * Created by PhpStorm.
 * User: buddha
 * Date: 14.07.2017
 * Time: 13:53
 */
class MeetingFormTest extends HumHubDbTestCase
{
    public function testCreateMeetingWithTimeZone()
    {
        $admin = User::findOne(1);
        $admin->language = 'de';
        $admin->time_zone = 'Europe/Berlin';
        $admin->save();

        $this->becomeUser('Admin');

        Yii::$app->i18n->autosetLocale();

        $space1 = Space::findOne(['id' => 1]);

        $meetingForm = new MeetingForm();
        $meetingForm->createNew($space1);

        $this->assertTrue($meetingForm->load([
            'Meeting' => [
                'title' => 'Test title',
                'location' => 'TestLocation',
                'room' => 'TestRoom',
            ],
            'MeetingForm' => [
                'startDate' => '15.07.17',
                'startTime' => '12:00',
                'endTime' => '15:00'
            ]
        ]));

        $this->assertTrue($meetingForm->save());

        $meeting = Meeting::findOne(1);

        $this->assertEquals('10:00', $meeting->begin);
        $this->assertEquals('13:00', $meeting->end);
        $this->assertEquals('11:00', $meeting->getFormattedBeginTime());
        $this->assertEquals('14:00', $meeting->getFormattedEndTime());
    }

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

    public function testSave()
    {
        // Admin has timeZone UTC
        $this->becomeUser('Admin');
        $this->_createTestMeetingForm();

        $meeting = Meeting::findOne(['id' => 1]);
        $this->assertNotEmpty($meeting);
        $this->assertEquals('2017-07-15', $meeting->date);
        $this->assertEquals('23:00', $meeting->begin);
        $this->assertEquals('23:30', $meeting->end);
        $this->assertEquals('2017-07-15', $meeting->date);
        $this->assertEquals('Test title', $meeting->title);
        $this->assertEquals('TestLocation', $meeting->location);
        $this->assertEquals('TestRoom', $meeting->room);
        $this->assertEquals('Some other dude', $meeting->external_participants);
    }

    public function testTimeZone()
    {
        // Admin has timeZone UTC
        $this->becomeUser('Admin');
        $this->_createTestMeetingForm();

        $meeting = Meeting::findOne(['id' => 1]);

        /**
         * Test formatted date for user with another timezone set.
         * Switch user timezone to UTC+02:00 - Europe/Berlin
         */
        Yii::$app->user->getIdentity()->time_zone = 'Europe/Berlin';
        Yii::$app->formatter->timeZone = 'Europe/Berlin';
        Yii::$app->formatter->locale = 'de';

        $this->assertEquals('16.07.17', $meeting->getFormattedStartDate());
        $this->assertEquals('00:00', $meeting->getFormattedBeginTime());
        $this->assertEquals('00:30', $meeting->getFormattedEndTime());
    }

    public function testEditWithDifferentTimeZone()
    {
        // Admin has timeZone UTC
        $admin = User::findOne(1);
        $admin->language = 'de';
        $admin->save();

        $this->becomeUser('Admin');
        $this->_createTestMeetingForm();

        // Load meeting with another timeZone setting in form
        $meeting = Meeting::findOne(['id' => 1]);
        $meetingForm = new MeetingForm(['meeting' => $meeting, 'timeZone' => 'Europe/Berlin']);

        // Check if date and time is aligned
        $this->assertEquals('16.07.17', $meetingForm->getFormattedStartDate());
        $this->assertEquals('00:00', $meetingForm->getFormattedBeginTime());
        $this->assertEquals('00:30', $meetingForm->getFormattedEndTime());

        // Save this form with changed date/time settings
        $this->assertTrue($meetingForm->load([
            'Meeting' => [],
            'MeetingForm' => [
                'startDate' => '18.07.2017',
                'startTime' => '02:00',
                'endTime' => '03:00'
            ]
        ]));

        $this->assertTrue($meetingForm->save());


        // Load the meeting again and check if the dates/times is still given for the timeZone
        $meeting = Meeting::findOne(1);

        $meetingForm = new MeetingForm(['meeting' => $meeting, 'timeZone' => 'Europe/Berlin']);
        #$this->assertEquals('18.07.17', $meetingForm->getFormattedStartDate());
        $this->assertEquals('01:00', $meetingForm->getFormattedBeginTime());
        $this->assertEquals('02:00', $meetingForm->getFormattedEndTime());

        // Load another form without specific time zone setting
        $meetingForm = new MeetingForm(['meeting' => $meeting]);
        $this->assertEquals('18.07.17', $meetingForm->getFormattedStartDate());
        $this->assertEquals('00:00', $meetingForm->getFormattedBeginTime());
        $this->assertEquals('01:00', $meetingForm->getFormattedEndTime());
    }
}