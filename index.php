<?php

include('../CalFileParser.php');

$cal = new CalFileParser();

pr($cal->parse('https://outlook.office365.com/owa/calendar/c5442ed2c52f432f8a848d9a8975f8d5@theminery.com/4a96e7f80425489e9df4af236e0ee9c07269803195692693419/calendar.ics'));

function pr($arr) {
    echo '<pre>';

    print_r("--------------------------\n");

    print_r($arr);

    print_r("\n--------------------------");

    echo '</pre>';
}