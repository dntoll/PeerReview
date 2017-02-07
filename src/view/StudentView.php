<?php

namespace view;




class NoUIDException extends \Exception {}

class StudentView {

	const NavigationAction = 'action';

	public static $UploadID = "fileToUpload";
	public static $UID = "userID";

	public static $NOUIDErrorMessage = "No used id provided";

	public function __construct(\Settings $s) {
		$this->settings = $s;
	}

	

	

	public function redirectBase(\model\UniqueID $uid) {
		$action = "";
		if (isset($_GET[self::NavigationAction])) {
			$action = "&".self::NavigationAction."=" . $_GET[self::NavigationAction] . "";
		}
		header("Location: index.php?" . $action);
		die();
	}


	public function getUID() : \model\UniqueID{


		if (isset($_GET[self::$UID]) == false) {
			if (isset($_SESSION[self::$UID]))
				return $_SESSION[self::$UID];
			throw new \view\NoUIDException();
		} else {

			$_SESSION[self::$UID] = new \model\UniqueID($_GET[self::$UID]);
			//return $_SESSION[self::$UID];
			$this->redirectBase($_SESSION[self::$UID]);
		}
	}

	

	



	public function showNothingToReviewNotice(\view\LayoutView $lv) : \view\LayoutView {
		$lv->addInformation("There are no documents for you to review at this point, you have to wait until more are posted");
		return $lv;
	}

	public function getWrongUserIDNote(\view\LayoutView $lv) : \view\LayoutView  {

		$lv->setHeaderText("No user found", "This is an error.");

		$lv->addWarning( "<h2>No active session.</h2> <p>This is probably due to that you have returned to the site when the session has ended. <a href=\"" . COURSE_PAGE_LINK . "\">Use the link provided by your course administrator on your course web space</a> to get access to PeerReview. If you have and still get this message, please contact your course administrator.</p>");
		return $lv;
	}

	public function showStudentNeedsToUploadFirst(\view\LayoutView $lv) : \view\LayoutView  {
		$lv->addWarning( "You need to upload a document before you can review other students documents");
		return $lv;
	}

	public function showNotTimeForReviews(\view\LayoutView $lv) : \view\LayoutView {
		$deadlineTimeString = $this->settings->getDeadlineTimeString();
				
		$lv->addWarning( "You need to wait until the deadline for uploading documents has expired. After $deadlineTimeString the review phase starts");
		return $lv;
	}

	public function notTimeToGiveFeedbackNotice(\view\LayoutView $lv) : \view\LayoutView {
		$deadlineTimeString = $this->settings->getFeedbackDeadlineTimeString();


		$lv->addWarning( "This is not the time for giving feedback to reviews, first you must make reviews. The feedback phase starts $deadlineTimeString");
		return $lv;
	}

	public function showNoAvailableTestPlans(\view\LayoutView $lv) : \view\LayoutView {
		$lv->addInformation( "There is currently no available document to a review. You have to wait until more documents are submitted.");
		return $lv;
	}

	public function showStudentShouldDoFeedbackNow(\view\LayoutView $lv) : \view\LayoutView {
		$lv->addInformation( "It is time to look at your feedback ");
		return $lv;
	}

	public function noReviewsRecievedYetNotice(\view\LayoutView $lv) : \view\LayoutView {
		$lv->addInformation( "You have not yet recieved any reviews on your document. You need to wait until reviews on your document are submitted before you can give feedback on those. ");
		return $lv;
	}

	


	public function studentWantsToUpload() {
		return isset($_GET[self::NavigationAction]) == false || $_GET[self::NavigationAction] === "upload";
	}

	public function studentWantsToReview() {
		return isset($_GET[self::NavigationAction]) && $_GET[self::NavigationAction] === "review";
	}

	public function studentWantsToFeedback() {
		return isset($_GET[self::NavigationAction]) && $_GET[self::NavigationAction] === "feedback";
	}

	public function studentWantsToViewGrade() {
		return isset($_GET[self::NavigationAction]) && $_GET[self::NavigationAction] === "score";	
	}

	public function showMenu(\model\UniqueID $student, string $submenu, bool $isTeacher) : string {

		$uid = $student->getName();
		$ret = "<div id=\"nav\"><ul>";
		$ret .=  "<li>
					
					<a class='menuItem' href='?".self::NavigationAction."=upload'>Upload your document</a>
				</li>
				<li>
					
					<a class='menuItem' href='?".self::NavigationAction."=review'>Review</a>
				</li>
				<li>
					
					<a class='menuItem' href='?".self::NavigationAction."=feedback'>Give Feedback on Reviews</a>
				</li>
				<li>
					<a class='menuItem' href='?".self::NavigationAction."=score'>Check scores</a>
				</li> ";
		
		if ($isTeacher) {
			$ret .=  "<li><a class='menuItem' href='?teacher&".self::NavigationAction."=plan'>Teacher View</a></li> ";

		} 
		$ret .= "</ul>$submenu</div>";
		return $ret;

	}

}