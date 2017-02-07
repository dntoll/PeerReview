<?php

namespace controller;


require_once("src/view/ScientistView.php");

class ScientistController {
	public function __construct(\model\StudentModel $m, \view\StudentView $sv, \Settings $s) {
		$this->m = $m;
		$this->view = new \view\ScientistView($m, $sv, $s);
	}


	public function doControl(\model\Uniqueid $teacher, \view\LayoutView $lv) : \view\LayoutView {

		return $this->view->show($teacher, $lv);
	}
}