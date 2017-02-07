<?php


namespace controller;


require_once("src/controller/UploadController.php");
require_once("src/controller/ReviewController.php");
require_once("src/controller/ReviewFeedbackController.php");
require_once("src/controller/GradeController.php");
require_once("src/view/UploadView.php");


class StudentController {

	private $v;
	private $m;
	private $s;

	public function __construct(\view\StudentView $v, \model\StudentModel $m, \Settings $s) {
		$this->v = $v;
		$this->m = $m;
		$this->s = $s;
		
	}

	public function doControl(\model\UniqueID $uid, \view\LayoutView $lv) : \view\LayoutView {
		if ($this->v->studentWantsToUpload($uid ) ) { 
			$uv = new \view\UploadView($this->s);

			$u = new UploadController($this->v, $this->m, $this->s, $uv);

			$lv = $uv->showHeader($lv);
			$lv = $u->doControl($uid, $lv);

		} else if ($this->v->studentWantsToFeedback($uid ) ) {
			$rv = new \view\ReviewFeedbackView($this->m, $uid);
			$r = new ReviewFeedbackController($this->v, $this->m, $this->s);
			$lv = $rv->showHeader($lv);

			$lv = $r->doControl($rv, $uid, $lv);
		} else if ($this->v->studentWantsToReview($uid ) ) {
			$rv = new \view\ReviewView($this->m);
			$r = new ReviewController($rv, $this->v, $this->m, $this->s);
			$lv = $rv->showHeader($lv);
			$lv =$r->doControl($uid, $lv);
		} else if ($this->v->studentWantsToViewGrade($uid ) ) {
			$vgv = new \view\ViewGradeView($this->m);
			$r = new GradeController($vgv, $this->m, $this->s);
			$lv = $vgv->showHeader($lv);
			$lv = $r->doControl($uid, $lv );
		} 
		 
		return $lv;
		//Second is review phase and replacing reviews
		
		//Third is review review phase giving grades to reviews
	}
}