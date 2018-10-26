<?php

date_default_timezone_set('America/Toronto');

$cfr = new CalFileRelay();
$dtStart = (isset($_REQUEST('dtstart'))) ? strtotime($_REQUEST('dtstart')) : strtotime('January 1, 1970');
$dtEnd = (isset($_REQUEST('dtend'))) ? strtotime($_REQUEST('dtend')) : strtotime('December 31, 2299');
$outFmt = (isset($_REQUEST('fmt'))) ? $_REQUEST('fmt') : 'json';
$calURL = (isset($_REQUEST('cal'))) ? $_REQUEST('cal') : null;
$calName = (isset($_REQUEST('calname'))) ? $_REQUEST('calname') : (is_null($calURL)) ? 'conference' : null;


class CalFileRelay {
    
    

}