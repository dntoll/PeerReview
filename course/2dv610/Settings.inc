<?php

/**
* The salt is used to create the user-tokens 
*/
define("COURSE_PRESS_SALT_USERID", 'A random salt');

/**
*
*/
define("COURSE_PAGE_LINK", "https://coursepage");

$month = 11;
$day = 06;
$year = 2016;
define("END_OF_UPLOAD_PHASE", mktime(0,0,0,$month, $day, $year));

$month = 11;
$day = 29;
$year = 2016;
define("END_OF_REVIEW_PHASE", mktime(0,0,0,$month, $day, $year));

/**
* User ids of teachers, note that these also must be in the students.inc, or?
*/
define("TEACHERS", array("usera1") );

define("STUDENT_FILE", "/vagrant/course/2dv610/students.inc");
define("UPLOAD_PATH", "/vagrant/data/2dv610");
define("COURSE_FILES", "/vagrant/course/2dv610/");
