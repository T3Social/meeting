<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

namespace meeting\acceptance;

use meeting\AcceptanceTester;

class MeetingCest
{
    
    public function testInstallAndCreateEntry(AcceptanceTester $I)
    {
        $I->amAdmin();
        $I->enableModule(1, 'meeting');

        $I->wantToTest('the creation of a meeting');
        $I->amGoingTo('submit a meeting form');

        $I->amOnSpace(1, '/meeting/index');
        $I->see('New meeting');
        $I->click('New meeting');

        $I->waitForText('Create new meeting');

        $I->fillField('Meeting[title]', 'Test Meeting 1');

        $I->click('#meetingform-startdate');
        $I->wait(1);
        $I->click('.ui-datepicker-today', '#ui-datepicker-div');

        $I->fillField('MeetingForm[startTime]', '12:00 PM');
        $I->fillField('MeetingForm[endTime]', '1:00 PM');


        $I->fillField('Meeting[location]', 'Test Location');
        $I->fillField('Meeting[room]', 'Test Room');

        $I->selectUserFromPicker('#participantPicker', 'Sara Tester');

        $I->click('#external-participants-link');
        $I->wait(1);
        $I->fillField('Meeting[external_participants]', 'Sonja Soja');

        $I->click('Save', '#globalModal');
        $I->wait(1);
        $I->waitForText('Test Meeting 1');
        $I->see('12:00 PM - 1:00 PM');
        $I->see('Test Location');
        $I->see('Test Room');
        $I->see('Today', '.label-danger');}
}