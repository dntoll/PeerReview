<?php

namespace controller;

require_once("src/view/ViewGradeView.php");

class GradeController {

	public function __construct(\view\ViewGradeView $vgv, \model\StudentModel $m, \Settings $s) {
		$this->m = $m;
		$this->settings = $s;
		$this->vgv = $vgv;
	}

	public function doControl(\model\UniqueID $uid, \view\LayoutView $lv) {
		
		
		if ($this->m->hasPreviouslyUploadedTestPlan($uid) === FALSE ) {
			$lv = $this->vgv->showMustUpload($lv);
		}
	
		$lv = $this->vgv->showGrade($uid, $lv);
		return $lv;
	}
}