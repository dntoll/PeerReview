<?php

/**
* The salt is used to create the user-tokens
*/
define("COURSE_PRESS_SALT_USERID", 'A random salt');

/**
*
*/
define("COURSE_PAGE_LINK", "https://coursepage");

/*
  A review source type.
*/
define("REVIEW_SOURCE_TYPE","pdf");

/****
  Language choise
****/
define("LANGUAGE","sv");


$day = 28;
$month = 02;
$year = 2017;
define("END_OF_UPLOAD_PHASE", mktime(0,0,0,$month, $day, $year));

$day = 17;
$month = 03;
$year = 2017;
define("END_OF_REVIEW_PHASE", mktime(0,0,0,$month, $day, $year));

/**
* User ids of teachers, note that these also must be in the students.inc, or?
*/
define("TEACHERS", array("admin1", "admin2") );

define("STUDENT_FILE", "/vagrant/course/2dv610/students.inc");
define("UPLOAD_PATH", "/vagrant/data/2dv610");
define("COURSE_FILES", "/vagrant/course/2dv610/");
define("INFORMATION_TEXT","information_text/");
