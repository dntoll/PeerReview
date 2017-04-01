<?php

namespace view;

use \Michelf\MarkdownExtra;
class UploadView {

	public function __construct(\Settings $s) {
		$this->settings = $s;
		$this->language = \Language::getLang();
	}

	public function studentTriesToUpload() : bool {
		return isset($_FILES[\view\StudentView::$UploadID]);
	}

	public function getUploadedFile() : \model\UploadedFile {
		assert($this->studentTriesToUpload());

		return new \model\UploadedFile($_FILES[\view\StudentView::$UploadID]);
	}

	public function showHeader(\view\LayoutView $lv) : \view\LayoutView {
		$lv->setHeaderText($this->language['headings']['upload_top_heading'], $this->language['headings']['upload_sub_heading']);

		return $lv;
	}

	public function showNotAFileError(\Exception $e, \view\LayoutView $lv) : \view\LayoutView {
		$lv->addInformation($e->getMessage());

		return $lv;

	}

	public function showUploadIsDoneNotice(\view\LayoutView $lv) : \view\LayoutView {

		$deadlineTimeString = $this->settings->getDeadlineTimeString();

		$lv->addInformation($this->language['upload']['upload_phase_done']. " [$deadlineTimeString]");

		return $lv;
	}

	public function showTestPlan(\model\TestPlan $f, \view\LayoutView $lv) : \view\LayoutView{

		$parsed = "<div class'testPlan'>".$this->language['upload']['upload_empty']."</div>";
		switch (REVIEW_SOURCE_TYPE) {
    case 'md':
			$parsed = MarkdownExtra::defaultTransform($f->getContent());
			$lv->addSection($this->language['navigation']['upload_uploaded_document'],"<h2>".$this->language['navigation']['upload_uploaded_document']."</h2>
					<div class='testPlan'>$parsed</div>") ;
        break;
    case 'pdf':
			$pdf = $f->getPdf();
			$lv->addSection($this->language['navigation']['upload_uploaded_document'],"<h2>".$this->language['navigation']['upload_uploaded_document']."</h2>
			<object data='$pdf' type='application/pdf' width='100%' height='842px'>
   		<p>".$this->language['pdf']['pdf_not_supported']." <a href='$pdf'>".$this->language['pdf']['pdf_anchor_text']."</a>.</p>
	 		</object>
			");
        break;
    default:
}

		return $lv;
	}

	public function showUpload(\view\LayoutView $lv) : \view\LayoutView {

		$deadlineTimeString = $this->settings->getDeadlineTimeString();


		if ($this->settings->isTimeToReview()) {
			$lv->addWarning("<strong>".$this->language['upload']['warning']."</strong> ".$this->language['upload']['one_more_upload_allowed']);
		}


		$lv->addSection($this->language['upload']['upload_heading_instructions'],"
	<div class=\"spotlight\">
		<div class=\"content\">
			<header class=\"major\">
			<h2>".$this->language['upload']['upload_heading_instructions']."</h2>
			</header>
			<p>".$this->language['upload']['upload_deadline_instructions']." $deadlineTimeString.</p>
		</div>
	</div>
	".file_get_contents(COURSE_FILES . INFORMATION_TEXT . "/uploadInstructions.inc"));

		$lv->addSection($this->language['upload']['upload_heading_form'], "
				<header class=\"major\">
					<h2>".$this->language['upload']['upload_heading_form']."</h2>
				</header>
				<form  method='post' enctype='multipart/form-data'>
    				".$this->language['upload']['upload_form_instructions']."
    				<input type='file' name='fileToUpload' id='fileToUpload' ><br/>
    				<input type='submit' value='".$this->language['upload']['upload_form_input']."  (.".REVIEW_SOURCE_TYPE.")' name='submit'>
				</form>
				");



		return $lv;
	}
}
