<?php


require_once("course/2dv610/Settings.inc");
require_once("Settings.php");
require_once("src/controller/MasterController.php");
require_once("src/model/UniqueID.php");


$ULRICA="73921bc50769518051d6a09f4adf3e83";
$ULRICA_DOCUMENT_CODE="218fd17cc358268e6359798f3fa2c058";

$GROUP_DOCUMENT_CODE="09051ca2e3b7ec42f82e4add1236a316";


$s = new \Settings();
$m = new \model\StudentModel($s);

//$ulrica = \model\UniqueID($ULRICA);



$tpd = new \model\TestPlanDAL($s);
$tprd = new \model\ReviewDAL($s);

$groupTestPlan = $tpd->getTestPlan($GROUP_DOCUMENT_CODE);
$ulricasTestPlan = $tpd->getTestPlan($ULRICA_DOCUMENT_CODE);

$reviewsOnUlricas = $tprd->getAllReviewsForPlan($ulricasTestPlan, $tpd);




foreach($reviewsOnUlricas as $review) {
	//swap test-plan
	$review->setTestPlan($groupTestPlan);

	//save as review
	$tprd->saveReview($review, $review->getIndex());

	//var_dump($review);	
	
}


var_dump( count($tprd->getAllReviewsForPlan($groupTestPlan, $tpd)) );
