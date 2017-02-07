<?php

namespace model;


/**
 * Represents the feedback given on a Review
 */
class ReviewFeedback {

	private $feedback;

	public function __construct(ReviewFactor $feedback, Uniqueid $feedbacker, TestPlanReview $item) {
		$this->feedback = $feedback;
		$this->feedbacker = $feedbacker;
		$this->item = $item;
	}

	public function getFeedbacker() : UniqueID {
		return $this->feedbacker;
	}

	public function getFeedback() : ReviewFactor {
		return $this->feedback;
	}

	public function getItem() : TestPlanReview {
		return $this->item;
	}

	public function getFeedbackText() : string{
		return $this->feedback->getText();
	}

	public function getGrading() : Grading {
		return $this->feedback->getGrading();
	}

	public function isFinished() : bool {
		return $this->feedback->isFinished();
	}

	public function setTeachersComment(ReviewFactor $factor) {
		$this->teacherFeedback = $factor;
	}

	public function getTeacherFeedback() : ReviewFactor {
		if (isset($this->teacherFeedback))
			return $this->teacherFeedback;
		else
			return new ReviewFactor("", new Grading());
	}
}