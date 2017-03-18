<?php

namespace model;

class Grading {
	const NOT_GRADED = -1;
	const NOT_AN_ATTEMPT = 0;
	const FAILED = 1;
	const SUFFICIENT = 2;
	const GOOD = 3;
	const EXCELLENT = 4;


	public function __construct(int $value = -1) {
		include("./language.php");

		require_once(COURSE_FILES . INFORMATION_TEXT . "gradingInterpretableValue.inc");
		$this->value = $value;
		if ($this->value < -1 || $this->value > 4)
			throw new \Exception($lang[LANGUAGE]['exceptions']['not_valid_grading']);
	}

	public function isFinished() : bool {
		return $this->value != self::NOT_GRADED;
	}

	public function getValue() : int {
		return $this->value;
	}


	public function getInterpretableValue() : string {
		switch($this->value) {
			case self::NOT_GRADED : return TEXT_NOT_GRADED;
			case self::NOT_AN_ATTEMPT : return  TEXT_NOT_AN_ATTEMPT;
			case self::FAILED : return TEXT_FAILED;
			case self::SUFFICIENT : return TEXT_SUFFICIENT;
			case self::GOOD : return TEXT_GOOD;
			case self::EXCELLENT : return TEXT_EXCELLENT;
		}
	}
}
