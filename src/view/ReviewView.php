<?php

namespace view;

require_once("GradingView.php");


use \Michelf\MarkdownExtra;

class ReviewView {


	public static $UploadID = "submit";
	public static $New = "New";

	public function __construct(\model\StudentModel $m) {

		$this->m = $m;


		$clarityGradeTitles = require(COURSE_FILES . INFORMATION_TEXT . "/clarityGrades.inc");


		$this->clarityFactorView = new ReviewFactorView("clarity", $clarityGradeTitles);


		$completenessGradeTitles = require(COURSE_FILES . INFORMATION_TEXT . "/completenessGrades.inc");


		$this->completenessFactorView = new ReviewFactorView("completeness", $completenessGradeTitles);


		$contentGrade = require(COURSE_FILES . INFORMATION_TEXT . "/contentGrades.inc");


		$this->contentFactorView = new ReviewFactorView("content", $contentGrade);
	}

	public function studentSubmitsReview() : bool {
		return isset($_POST[self::$UploadID]);
	}

	public function studentsCreatesNewReview() : bool {
		return isset($_GET[self::$New]);
	}

	public function getSubmittedReview(\model\TestPlanReview $ri) : \model\TestPlanReview {


		$ri->setClarity($this->clarityFactorView->getPostedFactor());
		$ri->setCompleteness($this->completenessFactorView->getPostedFactor());
		$ri->setContent($this->contentFactorView->getPostedFactor());

		return $ri;
	}

	public function getActiveReviewIndex() : int {
		if (isset($_GET["index"]))
			return intval($_GET["index"]);
		return 0;
	}

	public function showReview(\model\TestPlanReview $ri) : string {
		include("./language.php");

		$ret = "<div class=''>";
		$ret .= $this->clarityFactorView->getHTMLContent($ri->getClarity(), $lang[LANGUAGE]['review']['clarity']);
		$ret .= $this->completenessFactorView->getHTMLContent($ri->getCompleteness(), $lang[LANGUAGE]['review']['completeness']);
		$ret .= $this->contentFactorView->getHTMLContent($ri->getContent(), $lang[LANGUAGE]['review']['content']);
		$ret .= "</div>";


		return $ret;
	}

	public function showHeader(\view\LayoutView $lv) : \view\LayoutView{
		include("./language.php");
		$lv->setHeaderText($lang[LANGUAGE]['headings']['review_top_heading'], $lang[LANGUAGE]['headings']['review_sub_heading']);
		return $lv;
	}

	public function showReviewForm(\model\TestPlanReviewList $studentReviewItems, \model\UniqueID $user, int $index, \view\LayoutView $lv) : \view\LayoutView {
		include("./language.php");
		$ret ="<div class=\"spotlight\">
		<div class=\"content\">
			<header class=\"major\">
			<h2>".$lang[LANGUAGE]['navigation']['review_list_of_documents']."</h2>
			</header>
			<p>".$lang[LANGUAGE]['review']['show_review_form_instructions']."</p>

		</div>

	</div>";


		$ret .= "<div class='menu'><ul>";
		$uid = $user->getName();
		$canOpenNew = true;

		//ksort($studentReviewItems);
		foreach($studentReviewItems as $key => $ri) {
			$title = $lang[LANGUAGE]['review']['review_document']." # $key ";
			if ($ri->isFinished()) {

				if ($this->m->reviewHasFeedback($ri)) {
					$title .= "(".$lang[LANGUAGE]['review']['state_has_feedback'].")";
				} else {
					$title .= "(".$lang[LANGUAGE]['review']['state_complete'].")";
				}
			} else {
				$title .= "(".$lang[LANGUAGE]['review']['state_not_complete'].")";
				$canOpenNew = false;
			}



			if ($key === $index) {
				$ret .= "<li><span class='menuItemSelected'>$title</span></li>";
			} else {
				$ret .= "<li><a class='menuItem' href='?action=review&index=$key#Document+to+review+$key'>$title</a></li> ";
			}
		}

		if ($canOpenNew) {
			if ($this->m->getReviewableList($user)->count() > 0) {
				if ($studentReviewItems->getCount() === 0) {
					$ret .= "<li><a class='menuItem' href='?action=review&New'>".$lang[LANGUAGE]['review']['start_first_review']."</a></li>";
				} else {
					$ret .= "<li><a class='menuItem' href='?action=review&New'>".$lang[LANGUAGE]['review']['review_another']."</a></li>";
				}
			} else {
				$ret .= "<li>".$lang[LANGUAGE]['review']['no_more_documents']."</li>";
			}
		} else {
			$ret .= "<li>".$lang[LANGUAGE]['review']['complete_before_next']."</li>";
		}
		$ret .= "</ul></div>";

		$lv->addSection($lang[LANGUAGE]['navigation']['review_list_of_documents'], $ret);


		if ($studentReviewItems->_isset($index)) {
				$ri = $studentReviewItems->get($index);

				$lv->addSection($lang[LANGUAGE]['navigation']['review_document_to_review']." $index", $this->getTestPlanHTML($ri, $index));

				if ($this->m->reviewHasFeedback($ri)) { //We have past the time to submit
					$ret = "<header class=\"major\"><h2>".$lang[LANGUAGE]['review']['your_saved_review']."</h2></header>";
					$ret .= $this->showReview($ri, " ".$lang[LANGUAGE]['review']['on_document']." # $index ");
					$ret .= "<div class='Warning'>".$lang[LANGUAGE]['review']['cannot_change_feedbacked_review']."</div>";


					$lv->addSection($lang[LANGUAGE]['navigtaion']['review_saved_review'], $ret);
					$lv = $this->showFeedbackOnThisReview($ri, $user, $lv);
				} else {
					$ret = "<h2>".$lang[LANGUAGE]['navigation']['review_form']."</h2>";
					$ret .= $this->getReviewForm($ri, $user, $index);
					$lv->addSection($lang[LANGUAGE]['navigation']['review_form'], $ret);
				}
		}
		return $lv;
	}

	private function getTestPlanHTML(\model\TestPlanReview $ri, int $index) {
		include("./language.php");

		$ret = "<header class=\"major\"><h2>".$lang[LANGUAGE]['navigation']['review_document_to_review']." # $index</h2></header>";
		$tp = $ri->getTestPlan();

		switch (REVIEW_SOURCE_TYPE) {
    case 'md':
				$parsed = MarkdownExtra::defaultTransform($tp->getContent());
				$ret .= "<div class='testPlan'>$parsed</div>";
        break;
    case 'pdf':
			$pdf = $tp->getPdf();
			$ret .=
			"<object data='$pdf' type='application/pdf' width='100%' height='842px'>
   		<p>".$lang[LANGUAGE]['pdf']['pdf_not_supported']." <a href='$pdf'>".$lang[LANGUAGE]['pdf']['pdf_anchor_text']."</a>.</p>
	 		</object>
			";
        break;
    default:
}

		return $ret;
	}

	private function getReviewForm(\model\TestPlanReview $ri, \model\UniqueID $user, int $index) {
		include("./language.php");
	$formContent = "<div class='ReviewForm'>
		";
		if ($ri->isFinished() === false) {
			$formContent .= "<div class='Warning'>".$lang[LANGUAGE]['review']['complete_the_review']."</div>";
		}
		$formContent .= file_get_contents(COURSE_FILES . INFORMATION_TEXT . "/clarityDescription.inc");
		$formContent .= $this->clarityFactorView->getFormContent($ri->getClarity(), "reviewform");
		$formContent .= file_get_contents(COURSE_FILES . INFORMATION_TEXT . "/completenessDescription.inc");
		$formContent .= $this->completenessFactorView->getFormContent($ri->getCompleteness(), "reviewform");
		$formContent .= file_get_contents(COURSE_FILES . INFORMATION_TEXT . "/contentDescription.inc");
		$formContent .= $this->contentFactorView->getFormContent($ri->getContent(), "reviewform");
		$uid = $user->getName();


		$ret = "
					<form  method='post' enctype='multipart/form-data' id='reviewform'  action='?action=review&index=$index'>

				    $formContent
				    </br>
				    <input type='submit' value='".$lang[LANGUAGE]['review']['input_save_review']."' name='submit'><br/>
					</form>";
		return $ret . "</div>";
	}

	private function showFeedbackOnThisReview(\model\TestPlanReview $ri, \model\UniqueID $reviewer, \view\LayoutView $lv) : \view\LayoutView {
		include("./language.php");
		$ret = "<header class=\"major\"><h2>".$lang[LANGUAGE]['navigation']['review_feedback']."</h2></header>";
		$feedbacks = $this->m->getReviewFeedbackList($ri);

		foreach ($feedbacks as $key => $f) {
			$rfv = new ReviewFeedbackView($this->m, $reviewer);

			$ret .= $rfv->getFeedbackHTML($f, $lang[LANGUAGE]['review']['your_review_from']." # $key");

		}
		$lv->addSection($lang[LANGUAGE]['navigation']['review_feedback'], $ret);

		return $lv;

	}
}
