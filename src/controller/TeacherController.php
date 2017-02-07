<?php

namespace controller;


require_once("src/view/TeacherView.php");

class TeacherController {
	public function __construct(\model\StudentModel $m, \view\StudentView $sv, \Settings $s) {
		$this->m = $m;
		$this->view = new \view\TeacherView($m, $sv, $s);
	}


	public function doControl(\model\Uniqueid $teacher, \view\LayoutView $lv) : \view\LayoutView {

		if ($this->view->teacherReviewsReview()) {
			$review = $this->view->getReview();
			$reviewFactor = $this->view->getReviewFeedback();

			$this->m->saveTeacherReview($review, $reviewFactor);
		}

		if ($this->view->teacherReviewsFeedback()) {
			$feedback = $this->view->getFeedback();

			$studentFeedbacker = $feedback->getFeedbacker();
			$reviewFactor = $this->view->getFeedbackFeedback();
			

			$this->m->saveTeacherFeedback($feedback, $reviewFactor, $studentFeedbacker);
		}

		if ($this->view->teacherReviewsTestPlan()) {
			$tp = $this->view->getTestPlan();

			$reviewIndex = $this->m->teacherNewReviewAndReturnIndex($teacher, $tp);

			//echo "TeacherController : $reviewIndex was created?";
		}

		return $this->view->show($teacher, $lv);
	}
}