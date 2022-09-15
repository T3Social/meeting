Changelog
=========

0.5.4 (December 20, 2021)
-------------------------
- Enh #5274: Deprecate CompatModuleManager
- Enh #3: Initialize default meeting date
- Fix #30: Added separator for concatenated search attributes
- Fix #171: Fix errors of not found agenda entry


0.5.3 (June 02, 2021)
---------------------
- Fix #10: Fix print version


0.5.2 (July 30, 2021)
---------------------
- Fix #29: Prevent filter form submit on press Enter key
- Fix #114: PHP8 - Deprecate required parameters after optional parameters
- Fix #8: Fix misplaced button "New agenda entry" on change window width 


0.5.1 (March 23, 2021)
---------------------
- Enh: Minor Code Style Updates 


0.5.0 (March 2, 2021)
---------------------
- Chg #24: Changed min HumHub version to 1.8


0.4.5 (February 22, 2021)
-------------------------
- Enh #10: Use richtext editor instead of legacy markdown editor
- Fix #23: Fix date format


0.4.4 (January 27, 2021)
-----------------------
- Fix #69: List meeting permissions not correctly validated (Special thanks to @jrckmcsb for the security audit)
- Fix #69: Fix export ICS


0.4.3 (December 17, 2020)
--------------------
- Fix: No margin between paragraphs in agenda text output


0.4.2 (November 13, 2020)
--------------------
- Fix #18: WallEntry style is overwritten in meeting css
- Fix: Catch "Index is under processing" error


0.4.1 (November 11, 2020)
--------------------
- Fix: Date time validation fails for Chinese and Korean locale
- Enh: Small wallEntry style improvement

0.4.0 (November 04, 2020)
--------------------
- Chg: Changed min HumHub version to 1.7
- Chg: Changed min HumHub version to 1.7
- Chg: 1.7 wall stream entry migration

0.3.27 (April, 06, 2020)
--------------------
- Chg: Added 1.5 defer compatibility

0.3.26 (March, 03, 2020)
----------------------
- Fix: HumHub 1.4 issue in MeetingItemList with JSWidget data change
- Fix: Timezone of timezone chooser does not affect meeting time
- Fix: Meeting permissions displayed on container without meeting module installed (https://github.com/humhub/humhub/issues/3828)
- Enh: Enhanced exception handling in event handlers

0.3.25 (December 19, 2019)
----------------------
- Fix: Patched  issues with swedish locale in DBDateValidator for HumHub < v1.4

0.3.24 (October 16, 2019)
----------------------
- Enh: 1.4 nonce compatibility

0.3.23 (October 09, 2019)
----------------------
- Enh: Updated translations
- Fix: Meeting Entry drag interferes with mobile scrolling
- Enh: Added `uid` field for Meeting for calendar integration

0.3.22 (November 22, 2018)
----------------------
- Fix: Datepicker autocomplete dropdown overlaps actual date picker

0.3.21 (November 22, 2018)
----------------------
- Enh: Updated translations

0.3.20 (October 4, 2018)
----------------------
- Fix: Print page does not include time values of meeting items

0.3.19
----------------------
- Enh: Added feature for sending MeetingItems description and notes by mail module integration

0.3.18  (September 4, 2018)
----------------------
- Fix: Meeting Task after delete handler not executed
- Enh: Added MeetingTask integrity check

0.3.17  (July 2, 2018)
----------------------
- Fix: PHP 7.2 compatibility issues

0.3.16  (April 25, 2018)
----------------------
- Fix: compatibility with new task module

0.3.15  (April 25, 2018)
----------------------
- Fix: removed timezone from ics export

0.3.14 - 03.04.2017
----------------------
- Fix: Assigned task user layout broken
- Fix: Export ICS not working

0.3.13 - 30.11.2017
----------------------
- Fix: Removed `forceCopy`

0.3.12 - 30.11.2017
----------------------
- Fix: Drag/Drop of TOP issue

0.3.11 - 14.11.2017
----------------------
- Fix: Task date in print view
- Fix: MeetingItem file upload
- Fix: MeetingItem renderissue with big images

0.3.10 - 25.10.2017
----------------------
- Fix: ManageMeeting permission issue with edit protocol
- Fix: Item Dropdown

0.3.9 - 20.10.2017
----------------------
- Fix: Meeting Item Activity link
- Use of CalendarEntry Interface
- Fixed Today/Tomorrow issue with timezones

0.3.7 - 11.09.2017
----------------------
- Fix: Edit AM/PM formatted time
- Fix: Timepicker style alignment for AM/PM time

0.3.6 - 07.09.2017
----------------------
- Fix: Create in different timezone issue

0.3.5 - 16.08.2017
----------------------
- Fix: ICU 57.1 compatibility for time format HH.mm

0.3.4 - 16.08.2017
----------------------
- Enh: Duplicate agenda entries
- Fix: Task deadline render error

0.3.3 - 16.08.2017
----------------------
- Fix: Multiple items with empty duration not working

0.3.1 - 02.08.2017
----------------------
- Fix: create meeting item task not working

0.3 - 02.08.2017
----------------------
- Enh: Added pagination and filter bar for past meetings
- Enh: Added duplicate meeting function
- Enh: Enhanced usability
- Enh: Add Item moderators automatically to participant list
- Enh: Drag and Drop of MeetingItems.
- Enh: Major refactoring
- Enh: Shift existing items to other meetings

0.2.17 - 03.05.2017
----------------------
- Fix: Don't show MeetingItem content in stream


0.2.16 - 03.05.2017
----------------------
- Fix: Assign user to task not working
- Enh: Small usability enhancements
