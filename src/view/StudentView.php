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
		include("./language.php");
		$lv->addInformation($lang[LANGUAGE]['review']['nothing_to_review']);
		return $lv;
	}

	public function getWrongUserIDNote(\view\LayoutView $lv) : \view\LayoutView  {
		include("./language.php");

		$lv->setHeaderText($lang[LANGUAGE]['session']['no_user_found'], $lang[LANGUAGE]['session']['error']);

		$lv->addWarning($lang[LANGUAGE]['session']['no_active_session_info']);
		return $lv;
	}

	public function showStudentNeedsToUploadFirst(\view\LayoutView $lv) : \view\LayoutView  {
		include("./language.php");
		$lv->addWarning($lang[LANGUAGE]['review']['need_to_upload_first']);
		return $lv;
	}

	public function showNotTimeForReviews(\view\LayoutView $lv) : \view\LayoutView {
		include("./language.php");
		$deadlineTimeString = $this->settings->getDeadlineTimeString();

		$lv->addWarning($lang[LANGUAGE]['review']['not_time_for_reviews']." ".$deadlineTimeString);
		return $lv;
	}

	public function notTimeToGiveFeedbackNotice(\view\LayoutView $lv) : \view\LayoutView {
		include("./language.php");
		$deadlineTimeString = $this->settings->getFeedbackDeadlineTimeString();


		$lv->addWarning($lang[LANGUAGE]['feedback']['not_time_for_feedback']." ".$deadlineTimeString);
		return $lv;
	}

	public function showNoAvailableTestPlans(\view\LayoutView $lv) : \view\LayoutView {
		include("./language.php");
		$lv->addInformation($lang[LANGUAGE]['review']['nothing_to_review']);
		return $lv;
	}

	public function showStudentShouldDoFeedbackNow(\view\LayoutView $lv) : \view\LayoutView {
		include("./language.php");
		$lv->addInformation($lang[LANGUAGE]['feedback']['should_do_feedback_now']);
		return $lv;
	}

	public function noReviewsRecievedYetNotice(\view\LayoutView $lv) : \view\LayoutView {
		include("./language.php");
		$lv->addInformation($lang[LANGUAGE]['review']['no_reviews_yet']);
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
		include("./language.php");

		$uid = $student->getName();
		$ret = "<div id=\"nav\"><ul>";
		$ret .=  "<li>

					<a class='menuItem' href='?".self::NavigationAction."=upload'>".$lang[LANGUAGE]['navigation']['upload']."</a>
				</li>
				<li>

					<a class='menuItem' href='?".self::NavigationAction."=review'>".$lang[LANGUAGE]['navigation']['review']."</a>
				</li>
				<li>

					<a class='menuItem' href='?".self::NavigationAction."=feedback'>".$lang[LANGUAGE]['navigation']['feedback']."</a>
				</li>
				<li>
					<a class='menuItem' href='?".self::NavigationAction."=score'>".$lang[LANGUAGE]['navigation']['score']."</a>
				</li> ";

		if ($isTeacher) {
			$ret .=  "<li><a class='menuItem' href='?teacher&".self::NavigationAction."=plan'>".$lang[LANGUAGE]['navigation']['teacher']."</a></li> ";

		}
		$ret .= "</ul>$submenu</div>";
		return $ret;

	}

}
