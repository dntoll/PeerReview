<?php

namespace model;

class TestPlanReview {
	public function __construct(TestPlan $tp, UniqueID $reviewer, ReviewFactor $clarity, ReviewFactor $completeness, ReviewFactor $content, ReviewFactor $teacherFeedback) {
		$this->plan = $tp;
		$this->reviewer = $reviewer;
		$this->clarity = $clarity;
		$this->completeness = $completeness;
		$this->content = $content;
		$this->teacherFeedback = $teacherFeedback;

		$this->startTime = time();
		$this->revisionTime = $this->startTime;
	}

	public function setTestPlan(TestPlan $np)  {
		$this->plan = $np;
	}

	public function getTestPlan() : TestPlan {
		return $this->plan;
	}

	public function setIndex(int $index) {
		$this->orderOfImplementation = $index;
	}

	public function getIndex() {
		return $this->orderOfImplementation;
	}

	public function getClarity() : ReviewFactor {
		return $this->clarity;
	}

	public function getCompleteness() : ReviewFactor {
		return $this->completeness;
	}

	public function getContent() : ReviewFactor {
		return $this->content;
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

	public function getReviewer() : UniqueID {
		return $this->reviewer;
	}

	public function getTextCount() : int {
		return str_word_count($this->clarity->getText()) + str_word_count($this->completeness->getText()) + str_word_count($this->content->getText());
	}


	public function setClarity(ReviewFactor $newReview) {
		$this->clarity = $newReview;
		$this->revisionTime = time();
	}

	public function setCompleteness(ReviewFactor $newReview) {
		$this->completeness = $newReview;
		$this->revisionTime = time();
	}

	public function setContent(ReviewFactor $newReview) {
		$this->content = $newReview;
		$this->revisionTime = time();
	}

	public function getTotalTimeMinutes() : int {
		if (isset($this->startTime))
			return intval(($this->revisionTime - $this->startTime) / 60);
		else {
			$this->startTime = 0;
			return 0;
		}
	}

	public function isFinished() : bool {
		return $this->content->isFinished() && $this->completeness->isFinished() && $this->clarity->isFinished(); 
	}
}
