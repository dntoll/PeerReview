<?php

namespace controller;


require_once("src/view/StudentView.php");
require_once("src/model/StudentModel.php");
require_once("src/model/UploadedFile.php");
require_once("src/model/UniqueID.php");


class UploadController {

	private $v;
	private $m;

	public function __construct(\view\StudentView $v, \model\StudentModel $m, \Settings $s, \view\UploadView $uv) {
		$this->v = $v;
		$this->m = $m;
		$this->settings = $s;
		$this->uv = $uv;
	}

	public function doControl(\model\UniqueID $uid, \view\LayoutView $lv) : \view\LayoutView{
		
		try {

			if ($this->m->studentShouldUpload($uid ) ) { 

				if ($this->uv->studentTriesToUpload()) {
					$this->m->doSaveTestPlan($this->uv->getUploadedFile(), $uid);
					$lv->addInformation("Saved file");
				}
			}

			if ($this->m->studentShouldUpload($uid ) ) { 
				if ($this->m->hasPreviouslyUploadedTestPlan($uid)) {
					$upload = $this->m->getTestPlanFromUser($uid);

					$lv = $this->uv->showUpload($lv);
					$lv = $this->uv->showTestPlan($upload, $lv);
				} else {
					$lv = $this->uv->showUpload($lv);
				}

			} else {

				//Has uploaded and time has run out
				$upload = $this->m->getTestPlanFromUser($uid);

				$lv = $this->uv->showUploadIsDoneNotice($lv);
				$lv = $this->uv->showTestPlan($upload, $lv);
				
			}

			

			
		} catch (\model\NotAFileException $e) {
			$lv = $this->uv->showNotAFileError($e, $lv);
			$lv = $this->uv->showUpload($lv);
		}
		return $lv;

	}
}