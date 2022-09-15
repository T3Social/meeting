<?php
// Hide Debug toolbar if enabled
if (class_exists('yii\debug\Module')) {
    $this->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);
}
?>
<?php
$link = $contentContainer->createUrl('/meeting/index/view', array('id' => $meeting->id), true);
$uid = uniqid();
$title = $meeting->title;
$description = Yii::t('MeetingModule.views_index_getICS', 'Meeting details: %link%', array('%link%' => $link));
$location = $meeting->location . " " . $meeting->room;
$organizer = $meeting->content->createdBy->displayName;
$organizerMail = $meeting->content->createdBy->email;
$begin = Yii::$app->formatter->asDate($meeting->date, "yyyyMMdd") . "T" . Yii::$app->formatter->asTime($meeting->begin, "HHmmss");
$end = Yii::$app->formatter->asDate($meeting->date, "yyyyMMdd") . "T" . Yii::$app->formatter->asTime($meeting->end, "HHmmss");

$attendee = "";
foreach ($meeting->participants as $participant) {
    if ($participant->user->id == $meeting->content->createdBy->id)
        continue;
    $attendee .= "ATTENDEE;CN=" . $participant->user->displayName . ":MAILTO:" . $participant->user->email . "\n";
}
?>
<?php
header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $title . '.ics"');
?>
<?php if ($type == "private") : ?>
BEGIN:VCALENDAR
VERSION:2.0
PRODID:<?php echo $link."\n"; ?>
BEGIN:VEVENT
UID:HumHub-<?php echo $uid."\n"; ?>
DTSTART:<?php echo $begin."\n"; ?>
DTEND:<?php echo $end."\n"; ?>
SUMMARY:<?php echo $title."\n"; ?>
LOCATION:<?php echo $location."\n"; ?>
STATUS:CONFIRMED
DESCRIPTION:<?php echo $description."\n"; ?>
END:VEVENT
END:VCALENDAR
<?php else : ?> 
BEGIN:VCALENDAR
VERSION:2.0
PRODID:<?php echo $link."\n"; ?>
BEGIN:VEVENT
UID:HumHub-<?php echo $uid."\n"; ?>
DTSTART:<?php echo $begin."\n"; ?>
DTEND:<?php echo $end."\n"; ?>
SUMMARY:<?php echo $title."\n"; ?>
LOCATION:<?php echo $location."\n"; ?>
DESCRIPTION:<?php echo $description."\n"; ?>
ORGANIZER;CN="<?php echo $organizer; ?>":MAILTO:<?php echo $organizerMail."\n"; ?>
<?php echo $attendee."\n"; ?>
END:VEVENT
END:VCALENDAR
<?php endif; ?> 
