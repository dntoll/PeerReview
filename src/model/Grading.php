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
		$this->value = $value;
		if ($this->value < -1 || $this->value > 4)
			throw new \Exception("Not a valid Grading");
	}

	public function isFinished() : bool {
		return $this->value != self::NOT_GRADED;
	}

	public function getValue() : int {
		return $this->value;
	}

	public function getInterpretableValue() : string {
		switch($this->value) {
			case self::NOT_GRADED : return "Not graded ";
			case self::NOT_AN_ATTEMPT : return  "Not an attempt";
			case self::FAILED : return "Failed";
			case self::SUFFICIENT : return "Sufficient";
			case self::GOOD : return "Good";
			case self::EXCELLENT : return "Excellent";
		}
	}
}