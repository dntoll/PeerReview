<?php

namespace view;

require_once("ReviewFactorView.php");

class ReviewFeedbackView {

	private $m;

	public function __construct(\model\StudentModel $m, \model\UniqueID $feedbacker) {
		$this->m = $m;
		$this->language = \Language::getLang();


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


		$index = $this->getReviewIndex();

		if ($this->allReviews->_isset($index)) {
			return $this->allReviews->get($index);
		}
		throw new \Exception($this->language['exceptions']['exception_no_review_exists']);
	}


	public function userReviewsReview() : bool{
		return isset($_POST['submit']);
	}


	public function getReviewFeedback() : \model\ReviewFeedback {
		$feedbackReviewFactor = $this->feedbackFormView->getPostedFactor();
		return new \model\ReviewFeedback($feedbackReviewFactor, $this->feedbacker, $this->getActiveReview());
	}



	public function viewReview(ReviewView $rv, LayoutView $lv) : LayoutView {



		$lv = $this->showReviewSelection($lv);

		$studentReview = $this->getActiveReview();
		$index = $this->getReviewIndex();

		if ($studentReview->isFinished()) {

			if ($this->m->hasFeedbacked($this->feedbacker, $studentReview)) {
			} else {
				$lv->addWarning($this->language['feedback']['warning_need_to_submit_feedback']);
			}
			$ret = "<header class=\"major\"><h2>".$this->language['review']['review']." # $index ".$this->language['feedback']['on_your_document']."</h2></header>";
			$ret .= $rv->showReview($studentReview, " # $index");
			$lv->addSection($this->language['navigation']['feedback_read_review']." $index", $ret);

			$teacherHasSaidHisPiece = false;
			if ($this->m->hasFeedbacked($this->feedbacker, $studentReview)) {
				$feedback = $this->m->getReviewFeedback($this->feedbacker, $studentReview);

				if ($feedback->getTeacherFeedback()->getGrading()->isFinished()) {
					$teacherHasSaidHisPiece = true;
				}

			}

			if ($teacherHasSaidHisPiece == false)
				$lv->addSection($this->language['navigation']['feedback_give_feedback_on']." $index", $this->showReviewFeedbackForm($studentReview));
			else
				$lv->addSection($this->language['navigation']['feedback_your_feedback_on']." $index", $this->showReviewFeedbackAndTeacherNotes($studentReview));


		} else {
			$lv->addInformation($this->language['feedback']['reviewer_has_not_completed']);
		}


		return $lv;
	}

	public function getFeedbackHTML(\model\ReviewFeedback $feedback, string $title) {
		return $this->feedbackFormView->getHTMLContent($feedback->getFeedback(), $title);
	}

	private function getForm(\model\ReviewFeedback $feedback) {


		$formText = $this->feedbackFormView->getFormContent($feedback->getFeedback(), "feedbackform");


		$done = $feedback->isFinished() ?  "" : "<div class='Warning'>".$this->language['feedback']['warning_feedback_not_complete']."</div>";

		return "
		<header class=\"major\"><h2>".$this->language['feedback']['heading_give_feedback']."</h2></header>
<div class='FeedbackForm'>
	<p>".$this->language['feedback']['information_feedback']."</p>
	".file_get_contents(COURSE_FILES . INFORMATION_TEXT . "/feedbackInformation.inc")."
	<form  method='post' enctype='multipart/form-data' id='feedbackform'>
		$done
		$formText
		<br/>
		<input type='submit' value='".$this->language['feedback']['input_save_feedback']."' name='submit'>
	</form>

</div>

	";
	}




	private function showReviewFeedbackAndTeacherNotes(\model\TestPlanReview $item) : string {


		if ($this->m->hasFeedbacked($this->feedbacker, $item)) {
			$feedback = $this->m->getReviewFeedback($this->feedbacker, $item);
			return $this->getFeedbackHTML($feedback, $this->language['feedback']['your_feedback_to_reviewer']);
		}

		throw new \Exception($this->language['exceptions']['exception_only_on_teacher_feedback']);
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

		$lv->setHeaderText($this->language['headings']['feedback_top_heading'], $this->language['headings']['feedback_sub_heading']);
		return $lv;
	}

	private function showReviewSelection(\view\LayoutView $lv) : \view\LayoutView {

		$ret = "<header class=\"major\"><h2>".$this->language['navigation']['feedback_introduction'] ."</h2></header>";
		$ret .= "<p>".$this->language['feedback']['information_introduction']."</p>";

		$lv->addSection($this->language['navigation']['feedback_introduction'] , $ret);
		$ret = "";
		$index = $this->getReviewIndex();
		$uid = $this->feedbacker->getName();
		$ret .= "<div class='menu'><h2>".$this->language['feedback']['your_reviews']."</h2><ul>";
		$active = $this->getActiveReview();
		foreach ($this->allReviews as $key => $review) {
			if ($review->isFinished()) {

				if ($this->m->hasFeedbacked($this->feedbacker, $review)) {
					$feedback = $this->m->getReviewFeedback($this->feedbacker, $review);
					if ($feedback->isFinished())
						$status = "(".$this->language['feedback']['complete'].")";
					else
						$status = "(".$this->language['feedback']['not_complete'].")";
				} else {
					$status = "(".$this->language['feedback']['not_given_feedback'].")";
				}

				if ($index === $key) {
					$ret .= "<li><a class='menuItemSelected' href='?action=feedback&index=$key#Read+Review+$key'>".$this->language['review']['review']." # $key $status</a></li> ";
				} else {
					$ret .= "<li><a class='menuItem' href='?action=feedback&index=$key#Read+Review+$key'>".$this->language['review']['review']." # $key $status</a></li> ";
				}
			} else {
				//$ret .= "<span class='menuItemSelected' >Review # $key (not complete)</span> ";

			}

		}
		$ret .= "</ul></div>";
		$lv->addSection($this->language['navigation']['feedback_list_of_reviews'], $ret);

		return $lv;
	}
}
