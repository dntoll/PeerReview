<?php

namespace view;

require_once("ReviewFactorView.php");

class ReviewFeedbackView {

	private $m;

	public function __construct(\model\StudentModel $m, \model\UniqueID $feedbacker) {
		$this->m = $m;


		if ($m->hasPreviouslyUploadedTestPlan($feedbacker)) {
			$plan = $m->getTestPlanFromUser($feedbacker);
			$this->allReviews = $this->m->getAllReviews($plan);
		} else {
			$this->allReviews = array();
		}

		$this->feedbacker = $feedbacker;


		$this->reviewFeedbackGradeTitles = require(COURSE_FILES . INFORMATION_TEXT . "/reviewGrades.inc");

		$this->feedbackFormView = new ReviewFactorView("feedback", $this->reviewFeedbackGradeTitles);

	}

	private function getReviewIndex() {
		if (isset($_GET["index"])) {
			return $_GET["index"];
		} else {
			foreach ($this->allReviews as $key => $studentReviewItem) {
				if ($studentReviewItem->isFinished()) {
					return $key;
				}
			}
			return 0;
		}
	}


	public function getActiveReview() : \model\TestPlanReview {
		include("./language.php");

		$index = $this->getReviewIndex();

		if ($this->allReviews->_isset($index)) {
			return $this->allReviews->get($index);
		}
		throw new \Exception($lang[LANGUAGE]['exceptions']['exception_no_review_exists']);
	}


	public function userReviewsReview() : bool{
		return isset($_POST['submit']);
	}


	public function getReviewFeedback() : \model\ReviewFeedback {
		$feedbackReviewFactor = $this->feedbackFormView->getPostedFactor();
		return new \model\ReviewFeedback($feedbackReviewFactor, $this->feedbacker, $this->getActiveReview());
	}



	public function viewReview(ReviewView $rv, LayoutView $lv) : LayoutView {
		include("./language.php");


		$lv = $this->showReviewSelection($lv);

		$studentReview = $this->getActiveReview();
		$index = $this->getReviewIndex();

		if ($studentReview->isFinished()) {

			if ($this->m->hasFeedbacked($this->feedbacker, $studentReview)) {
			} else {
				$lv->addWarning($lang[LANGUAGE]['feedback']['warning_need_to_submit_feedback']);
			}
			$ret = "<header class=\"major\"><h2>".$lang[LANGUAGE]['review']['review']." # $index ".$lang[LANGUAGE]['feedback']['on_your_document']."</h2></header>";
			$ret .= $rv->showReview($studentReview, " # $index");
			$lv->addSection($lang[LANGUAGE]['navigation']['feedback_read_review']." $index", $ret);

			$teacherHasSaidHisPiece = false;
			if ($this->m->hasFeedbacked($this->feedbacker, $studentReview)) {
				$feedback = $this->m->getReviewFeedback($this->feedbacker, $studentReview);

				if ($feedback->getTeacherFeedback()->getGrading()->isFinished()) {
					$teacherHasSaidHisPiece = true;
				}

			}

			if ($teacherHasSaidHisPiece == false)
				$lv->addSection($lang[LANGUAGE]['navigation']['feedback_give_feedback_on']." $index", $this->showReviewFeedbackForm($studentReview));
			else
				$lv->addSection($lang[LANGUAGE]['navigation']['feedback_your_feedback_on']." $index", $this->showReviewFeedbackAndTeacherNotes($studentReview));


		} else {
			$lv->addInformation($lang[LANGUAGE]['feedback']['reviewer_has_not_completed']);
		}


		return $lv;
	}

	public function getFeedbackHTML(\model\ReviewFeedback $feedback, string $title) {
		return $this->feedbackFormView->getHTMLContent($feedback->getFeedback(), $title);
	}

	private function getForm(\model\ReviewFeedback $feedback) {
		include("./language.php");

		$formText = $this->feedbackFormView->getFormContent($feedback->getFeedback(), "feedbackform");


		$done = $feedback->isFinished() ?  "" : "<div class='Warning'>".$lang[LANGUAGE]['feedback']['warning_feedback_not_complete']."</div>";

		return "
		<header class=\"major\"><h2>".$lang[LANGUAGE]['feedback']['heading_give_feedback']."</h2></header>
<div class='FeedbackForm'>
	<p>".$lang[LANGUAGE]['feedback']['information_feedback']."</p>
	".file_get_contents(COURSE_FILES . INFORMATION_TEXT . "/feedbackInformation.inc")."
	<form  method='post' enctype='multipart/form-data' id='feedbackform'>
		$done
		$formText
		<br/>
		<input type='submit' value='".$lang[LANGUAGE]['feedback']['input_save_feedback']."' name='submit'>
	</form>

</div>

	";
	}




	private function showReviewFeedbackAndTeacherNotes(\model\TestPlanReview $item) : string {
		include("./language.php");

		if ($this->m->hasFeedbacked($this->feedbacker, $item)) {
			$feedback = $this->m->getReviewFeedback($this->feedbacker, $item);
			return $this->getFeedbackHTML($feedback, $lang[LANGUAGE]['feedback']['your_feedback_to_reviewer']);
		}

		throw new \Exception($lang[LANGUAGE]['exceptions']['exception_only_on_teacher_feedback']);
	}

	private function showReviewFeedbackForm(\model\TestPlanReview $item) : string {

		if ($this->m->hasFeedbacked($this->feedbacker, $item)) {
			$feedback = $this->m->getReviewFeedback($this->feedbacker, $item);
		} else {
			//extract data
			$feedback = new \model\ReviewFeedback(new \model\ReviewFactor("", new \model\Grading()), $this->feedbacker, $this->getActiveReview());
		}

		return $this->getForm($feedback);
	}

	public function showHeader(\view\LayoutView $lv) : \view\LayoutView {
		include("./language.php");
		$lv->setHeaderText($lang[LANGUAGE]['headings']['feedback_top_heading'], $lang[LANGUAGE]['headings']['feedback_sub_heading']);
		return $lv;
	}

	private function showReviewSelection(\view\LayoutView $lv) : \view\LayoutView {
		include("./language.php");
		$ret = "<header class=\"major\"><h2>".$lang[LANGUAGE]['navigation']['feedback_introduction'] ."</h2></header>";
		$ret .= "<p>".$lang[LANGUAGE]['feedback']['information_introduction']."</p>";

		$lv->addSection($lang[LANGUAGE]['navigation']['feedback_introduction'] , $ret);
		$ret = "";
		$index = $this->getReviewIndex();
		$uid = $this->feedbacker->getName();
		$ret .= "<div class='menu'><h2>".$lang[LANGUAGE]['feedback']['your_reviews']."</h2><ul>";
		$active = $this->getActiveReview();
		foreach ($this->allReviews as $key => $review) {
			if ($review->isFinished()) {

				if ($this->m->hasFeedbacked($this->feedbacker, $review)) {
					$feedback = $this->m->getReviewFeedback($this->feedbacker, $review);
					if ($feedback->isFinished())
						$status = "(".$lang[LANGUAGE]['feedback']['complete'].")";
					else
						$status = "(".$lang[LANGUAGE]['feedback']['not_complete'].")";
				} else {
					$status = "(".$lang[LANGUAGE]['feedback']['not_given_feedback'].")";
				}

				if ($index === $key) {
					$ret .= "<li><a class='menuItemSelected' href='?action=feedback&index=$key#Read+Review+$key'>".$lang[LANGUAGE]['review']['review']." # $key $status</a></li> ";
				} else {
					$ret .= "<li><a class='menuItem' href='?action=feedback&index=$key#Read+Review+$key'>".$lang[LANGUAGE]['review']['review']." # $key $status</a></li> ";
				}
			} else {
				//$ret .= "<span class='menuItemSelected' >Review # $key (not complete)</span> ";

			}

		}
		$ret .= "</ul></div>";
		$lv->addSection($lang[LANGUAGE]['navigation']['feedback_list_of_reviews'], $ret);

		return $lv;
	}
}
