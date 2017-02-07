<?php

namespace model;

class ReviewFactor {
	private $grading;
	private $comment;

	public function __construct(string $comment, Grading $grading) {
		$this->comment = $comment;
		$this->grading = $grading;
	}

	public function getText() {
		return htmlspecialchars($this->comment);	
	}

	public function getGrading() {
		return $this->grading;	
	}

	public function isFinished() : bool {
		return mb_strlen($this->comment) > 0 && 
				$this->grading->isFinished();
	}

}