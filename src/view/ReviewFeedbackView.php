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

		$index = $this->getReviewIndex();

		if ($this->allReviews->_isset($index)) {
			return $this->allReviews->get($index);
		}
		throw new \Exception("No review exists");
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
				$lv->addWarning("Warning: You need to submit feedback for this review.");
			}
			$ret = "<header class=\"major\"><h2>Review # $index on your document</h2></header>";
			$ret .= $rv->showReview($studentReview, " # $index");
			$lv->addSection("Read Review $index", $ret);

			$teacherHasSaidHisPiece = false;
			if ($this->m->hasFeedbacked($this->feedbacker, $studentReview)) {
				$feedback = $this->m->getReviewFeedback($this->feedbacker, $studentReview);

				if ($feedback->getTeacherFeedback()->getGrading()->isFinished()) {
					$teacherHasSaidHisPiece = true;
				}

			}

			if ($teacherHasSaidHisPiece == false)
				$lv->addSection("Give Feedback on $index", $this->showReviewFeedbackForm($studentReview));
			else
				$lv->addSection("Your Feedback on $index", $this->showReviewFeedbackAndTeacherNotes($studentReview));


		} else {
			$lv->addInformation("The reviewer has not completed the review.");
		}


		return $lv;
	}

	public function getFeedbackHTML(\model\ReviewFeedback $feedback, string $title) {
		return $this->feedbackFormView->getHTMLContent($feedback->getFeedback(), $title);
	}

	private function getForm(\model\ReviewFeedback $feedback) {

		$formText = $this->feedbackFormView->getFormContent($feedback->getFeedback(), "feedbackform");


		$done = $feedback->isFinished() ?  "" : "<div class='Warning'>Warning: This Feedback is not complete.</div>";

		return "
		<header class=\"major\"><h2>Give feedback on the review:</h2></header>
<div class='FeedbackForm'>
	<p>You should respond to the review you are given.</p>

	<ul>
	<li>A good review should be truthful (correct)</li>
	<li>A good review should helpful give clues to what is good and what is not and suggest changes.</li>
	<li>A good review should be thorough and complete</li>
	<li>A good review may still be of a different oppinion than yours.</li>
	</ul>

A bad grade does not automatically mean that you or the reviewer gets a low grade, it is an indication of that something is not right.
If you think something is wrong with the review, state your view in the comments. Be polite. You are anonymous to the other student but not to the teaching assistants.

Remember different people have different views and may interpret the same information differently. Learn from this, how could you have written your document in a way that this reviewer would have liked?

	$done

<p>Comment on what you learned from this review also motivate your grading of this review. </p>

<form  method='post' enctype='multipart/form-data' id='feedbackform'>

	$formText
	<br/>
	<input type='submit' value='Save review feedback' name='submit'>
</form>

</div>

	";
	}




	private function showReviewFeedbackAndTeacherNotes(\model\TestPlanReview $item) : string {

		if ($this->m->hasFeedbacked($this->feedbacker, $item)) {
			$feedback = $this->m->getReviewFeedback($this->feedbacker, $item);
			return $this->getFeedbackHTML($feedback, "Your feedback to this reviewer");
		}

		throw new \Exception("Should only happen when we have feedback from teacher ");
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
		$lv->setHeaderText("View Reviews and Give Feedback on Reviews", "During this phase you are to view the reviews your document has generated.");
		return $lv;
	}

	private function showReviewSelection(\view\LayoutView $lv) : \view\LayoutView {
		$ret = "<header class=\"major\"><h2>Introduction</h2></header>";
		$ret .= "<p>During this phase you are to view the reviews your document has generated. You should also grade these reviews and provide a comment on the reasoning behind your grading. Note that you should not provide personal information in these comments and you are anonymous for the student that reviewed your document. However, you are not anonymous to the teacher.</p>";

		$lv->addSection("Introduction", $ret);
		$ret = "";
		$index = $this->getReviewIndex();
		$uid = $this->feedbacker->getName();
		$ret .= "<div class='menu'><h2>Your reviews</h2><ul>";
		$active = $this->getActiveReview();
		foreach ($this->allReviews as $key => $review) {
			if ($review->isFinished()) {

				if ($this->m->hasFeedbacked($this->feedbacker, $review)) {
					$feedback = $this->m->getReviewFeedback($this->feedbacker, $review);
					if ($feedback->isFinished())
						$status = "(Complete)";
					else
						$status = "(not complete!)";
				} else {
					$status = "(You have not given feedback on this review)";
				}

				if ($index === $key) {
					$ret .= "<li><a class='menuItemSelected' href='?action=feedback&index=$key#Read+Review+$key'>Review # $key $status</a></li> ";
				} else {
					$ret .= "<li><a class='menuItem' href='?action=feedback&index=$key#Read+Review+$key'>Review # $key $status</a></li> ";
				}
			} else {
				//$ret .= "<span class='menuItemSelected' >Review # $key (not complete)</span> ";

			}

		}
		$ret .= "</ul></div>";
		$lv->addSection("List of reviews", $ret);

		return $lv;
	}
}
