<?php

namespace view;

class ScientistView {

	private static $TEACHER_REVIEW_DOCUMENT = "TeacherReviewDocument";


	public function __construct(\model\StudentModel $m, \Settings $s) {
		$this->m = $m;
	}
}