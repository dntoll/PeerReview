<?php

namespace view;

use \Michelf\MarkdownExtra;

class UploadView {

	public function __construct(\Settings $s) {
		$this->settings = $s;
	}

	public function studentTriesToUpload() : bool {
		return isset($_FILES[\view\StudentView::$UploadID]);
	}

	public function getUploadedFile() : \model\UploadedFile {
		assert($this->studentTriesToUpload());

		return new \model\UploadedFile($_FILES[\view\StudentView::$UploadID]);
	}

	public function showHeader(\view\LayoutView $lv) : \view\LayoutView {

		$lv->setHeaderText("Upload your Document", "Write your document in Markdown format and upload the file below.");

		return $lv;
	}

	public function showNotAFileError(\Exception $e, \view\LayoutView $lv) : \view\LayoutView {
		$lv->addInformation($e->getMessage());

		return $lv;

	}

	public function showUploadIsDoneNotice(\view\LayoutView $lv) : \view\LayoutView {
		$deadlineTimeString = $this->settings->getDeadlineTimeString();

		$lv->addInformation("You cannot change the uploaded document at this point. The deadline for uploading documents has passed [$deadlineTimeString]");

		return $lv;
	}

	public function showTestPlan(\model\TestPlan $f, \view\LayoutView $lv) : \view\LayoutView{
		//$Parsedown = new \Parsedown();

		//$parsed = $Parsedown->text($f->getContent());

		$parsed = "<div class'testPlan'>EMPTY</div>";
		switch (REVIEW_SOURCE_TYPE) {
    case 'md':
			$parsed = MarkdownExtra::defaultTransform($f->getContent());
			$lv->addSection("The uploaded document","<h2>The uploaded document</h2>
					<div class='testPlan'>$parsed</div>") ;
        break;
    case 'pdf':
			$pdf = $f->getPdf();
/*
			$lv->addSection("The uploaded document","<h2>The uploaded document</h2>
			<object width='100%' class='testPlan' data='$pdf' type='application/pdf'>
		 <embed src='$pdf' type='application/pdf' />
 </object>");
*/
			$lv->addSection("The uploaded document","<h2>The uploaded document</h2>
			<div class='testPlan'>
			<embed src='$pdf' type='application/pdf' width='100%' height='100%'>
			</div>");
        break;
    default:
}

		return $lv;
	}

	public function showUpload(\view\LayoutView $lv) : \view\LayoutView {
		$deadlineTimeString = $this->settings->getDeadlineTimeString();


		if ($this->settings->isTimeToReview()) {
			$lv->addWarning("<strong>WARNING</strong> Since the time for uploading has ended, only a single upload will be allowed! Make sure you double check that you upload a correct file.");
		}


		$lv->addSection("Upload instructions","
	<div class=\"spotlight\">
		<div class=\"content\">
			<header class=\"major\">
			<h2>Upload instructions</h2>
			</header>
			<p>The uploaded file can be changed up until $deadlineTimeString. After that only a one time upload can happen (no changes of uploaded file) and no garanties are made that you are going to get reviews.</p>
		</div>

	</div>

			<ul>
			<li>The strategy, test-plan, test-cases and test-report should be written as a single MarkDown formatted (.md) file</li>
			<li>The document should be anonymous, no names or traces of who you are should be in the document. Instead use role-names like “test-lead”, “scrum-master” or “product-owner”. The document and other text are not anonymous to course management.</li>
			<li>All students in the group should upload the exact same document, please make sure that if you update the document and resubmit that all students update it.</li>
			<li>The document or parts of it may not be shared between groups.</li>
			<li>Read the review task in order to find out more about how to write a successful document.</li>
			<li>If you are more than one student in your group every student must upload the exact same file.</li>
			</ul>
		");

		$lv->addSection("Upload form", "
				<header class=\"major\">
					<h2>Upload form</h2>
				</header>
				<form  method='post' enctype='multipart/form-data'>
    				Select .md Document to upload:
    				<input type='file' name='fileToUpload' id='fileToUpload' ><br/>
    				<input type='submit' value='Upload TestPlan (.md)' name='submit'>
				</form>
				");



		return $lv;
	}
}
