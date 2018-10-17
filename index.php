<?php

//echo 'foo';

include('./CalFileParser.php');

$cal = new CalFileParser();

$arCal = $cal->parse('https://outlook.office365.com/owa/calendar/c5442ed2c52f432f8a848d9a8975f8d5@theminery.com/4a96e7f80425489e9df4af236e0ee9c07269803195692693419/calendar.ics');
$arEvents = array();
$dateToday = strtotime('midnight');

foreach ($arCal as $calEvent) {
    if ($calEvent['DTSTART']->getTimestamp() > $dateToday) {
        array_push($arEvents, $calEvent);
    }
}

//pr($arCal);
$startingDate = strtotime('last monday', strtotime('next sunday'));
$arDates = getCalDates($startingDate);


?>
<!DOCTYPE html>
<html>
<head>
<title>WRKHUB Conference Room</title>
</head>
<body>

<h1>Conference Room Schedule</h1>
<!-- <?php print_r($arEvents); ?> -->
<!-- <?php print_r($arDates); ?> -->
<table border="1">
    <tr>
        <th>&nbsp;</th>
        <th><?php echo getWeek(0); ?></th>
        <th><?php echo getWeek(1); ?></th>
    </tr><tr>
        <td>Monday</td>
        <td><?php echo eventsForDate($arDates[0], $arEvents); ?></td>
        <td>&nbsp;</td>
    </tr><tr>
        <td>Tuesday</td>
        <td><?php echo eventsForDate($arDates[1], $arEvents); ?></td>
        <td>&nbsp;</td>
    </tr><tr>
        <td>Wednesday</td>
        <td><?php echo eventsForDate($arDates[2], $arEvents); ?></td>
        <td>&nbsp;</td>
    </tr><tr>
        <td>Thursday</td>
        <td><?php echo eventsForDate($arDates[3], $arEvents); ?></td>
        <td>&nbsp;</td>
    </tr><tr>
        <td>Friday</td>
        <td><?php echo eventsForDate($arDates[4], $arEvents); ?></td>
        <td>&nbsp;</td>
    </tr>
</table>

</body>
</html>

<?php
function eventsForDate($thisDate, $arEvents) {
    $out = '<!-- ' . date('Y-m-d', $thisDate) . ' -->';
    $tomorrow = strtotime('tomorrow', $thisDate);
    foreach($arEvents as $event) {
        if (($event['DTSTART']->getTimestamp() > $thisDate) && ($event['DTSTART']->getTimestamp() < $tomorrow)) {
            $out .= '<div>' . date('H:i', $event['DTSTART']->getTimestamp()) . ' - ' . date('H:i', $event['DTEND']->getTimestamp()) . '</div>';
        }
    }
    return $out;
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