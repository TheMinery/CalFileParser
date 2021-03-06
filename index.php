<?php

//echo 'foo';
date_default_timezone_set('America/Toronto');

include('./CalFileParser.php');

$cal = new CalFileParser();

$arCalConference = $cal->parse('https://outlook.office365.com/owa/calendar/c5442ed2c52f432f8a848d9a8975f8d5@theminery.com/4a96e7f80425489e9df4af236e0ee9c07269803195692693419/calendar.ics');
$arCalMeeting = $cal->parse('https://outlook.office365.com/owa/calendar/159896db09b34ffb967424656bcbab59@theminery.com/141079b1c7ee4daaa6722fa1925fa95212132363614565888136/calendar.ics');
//$startingDate = strtotime('last monday', strtotime('next sunday'));
$startingDate = strtotime('yesterday');
$endingDate = strtotime('+9 days', $startingDate);
$arConference = getCurrentEvents($arCalConference, $startingDate, $endingDate);
$arMeeting = getCurrentEvents($arCalMeeting, $startingDate, $endingDate);
//$arEvents = array();
//$dateToday = strtotime('midnight');



//pr($arCal);

//$arDates = getCalDates($startingDate);

?>
<!DOCTYPE html>
<html>
<head>
<title>WRKHUB Conference Room</title>
<meta http-equiv="refresh" content="300">
<link rel="stylesheet" type="text/css" href="./css/cfp.css" />
</head>
<body>
<!-- <?php print_r($arCalMeeting); ?> -->
<!-- <?php print_r($arMeeting); ?> -->
<section>
    <div id="conference">
    <h1>Conference Room Schedule</h1>
<?php $thisDay = $startingDate; ?>
<?php for($i=0; $i<8; $i++) : ?>
    <?php $thisDay = strtotime('+1 day', $thisDay);; ?>
    <?php if ((date('l', $thisDay) != "Saturday") && (date('l', $thisDay) != "Sunday")) : ?>
    <div class="day">
        <h3><?php echo date('l F jS', $thisDay); ?></h3>
        <div><?php echo eventsForDate($thisDay, $arConference); ?></div>
    </div>
    <?php endif; ?>
<?php endfor; ?>
    </div>
    <div id="meeting">
    <h1>Meeting Room Schedule</h1>
<?php $thisDay = $startingDate; ?>
<?php for($i=0; $i<8; $i++) : ?>
    <?php $thisDay = strtotime('+1 day', $thisDay); ?>
    <?php if ((date('l', $thisDay) != "Saturday") && (date('l', $thisDay) != "Sunday")) : ?>
    <div class="day">
        <h3><?php echo date('l F jS', $thisDay); ?></h3>
        <div><?php echo eventsForDate($thisDay, $arMeeting); ?></div>
    </div>
    <?php endif; ?>
<?php endfor; ?>
    </div>
</section>
</body>
</html>

<?php
function eventsForDate($thisDate, $arEvents) {
    $out = '';
    $tomorrow = strtotime('tomorrow', $thisDate);
    foreach($arEvents as $event) {
        if (($event['DTSTART']->getTimestamp() > $thisDate) && ($event['DTSTART']->getTimestamp() < $tomorrow)) {
            //$out .= '<!-- DTSTART = ' . date('Y-m-d H:i:s', $event['DTSTART']->getTimestamp()) . ' - DTEND = ' . date('Y-m-d H:i:s', $event['DTEND']->getTimestamp()) . ' -->' . "\n";
            $out .= '<div>' . date('H:i', $event['DTSTART']->getTimestamp()) . ' - ' . date('H:i', $event['DTEND']->getTimestamp()) . '</div>' . "\n";
        }
    }
    $out = ($out == '') ? 'Nothing scheduled!':$out;
    return $out;
}

function getCurrentEvents($arCal, $startingDate, $endingDate) {
    $arEvents = array();
    foreach ($arCal as $calEvent) {
        if (($calEvent['DTSTART']->getTimestamp() > $startingDate) && ($calEvent['DTSTART']->getTimestamp() < $endingDate))  {
            array_push($arEvents, $calEvent);
        }
    }
    return $arEvents;
}

function getCalDates($startDate) {
    $arOut = array();
    $thisDate = $startDate;
    for ($i = 0; $i < 12; $i ++) {
        $thisDate = strtotime('+' . $i . ' days', $startDate);
        if ((date('D', $thisDate) != "Sat") && (date('D', $thisDate) != "Sun")) {
            array_push($arOut, $thisDate);
        }
    }
    return $arOut;
}


function getWeek($offset) {
    $thisMonday = strtotime('last monday', strtotime('next sunday'));
    $lastMonday = strtotime('last monday', $thisMonday);
    $nextMonday = strtotime('next monday', $thisMonday);
    $out = null;
    switch ($offset) {
        case -1:
            $out = $lastMonday;
            break;
        case 0:
            $out = $thisMonday;
            break;
        case 1:
            $out = $nextMonday;
            break;
    }
    return date('M j', $out) . ' - ' . date('M j', strtotime('next friday', $out));
}

function pr($arr) {
    echo '<pre>';

    print_r("--------------------------\n");

    print_r($arr);

    print_r("\n--------------------------");

    echo '</pre>';
}