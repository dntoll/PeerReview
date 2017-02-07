<?php

namespace view;

require_once("GradingView.php");


use \Michelf\MarkdownExtra;

class ReviewView {


	public static $UploadID = "submit";
	public static $New = "New";

	public function __construct(\model\StudentModel $m) {
		
		$this->m = $m;


		$clarityGradeTitles = require(COURSE_FILES . "/clarityGrades.inc"); 


		$this->clarityFactorView = new ReviewFactorView("clarity", $clarityGradeTitles);


		$completenessGradeTitles = require(COURSE_FILES . "/completenessGrades.inc"); 


		$this->completenessFactorView = new ReviewFactorView("completeness", $completenessGradeTitles);


		$contentGrade = require(COURSE_FILES . "/contentGrades.inc"); 
		

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
		$ret = "<div class=''>";
		$ret .= $this->clarityFactorView->getHTMLContent($ri->getClarity(), "Clarity");
		$ret .= $this->completenessFactorView->getHTMLContent($ri->getCompleteness(), "Completeness");
		$ret .= $this->contentFactorView->getHTMLContent($ri->getContent(), "Content");
		$ret .= "</div>";

		
		return $ret;
	}

	public function showHeader(\view\LayoutView $lv) : \view\LayoutView{
		
		$lv->setHeaderText("Review documents", "You should review and grade other students documents. The more the better.");
		return $lv;
	}
	
	public function showReviewForm(\model\TestPlanReviewList $studentReviewItems, \model\UniqueID $user, int $index, \view\LayoutView $lv) : \view\LayoutView {
		$ret ="<div class=\"spotlight\">
		<div class=\"content\">
			<header class=\"major\">
			<h2>List of documents to review</h2>
			</header>
			<p>You can do as many reviews as you like. Your reviews that get the highest feedback will determine your grade. Note that reviews that got feedback can no longer be changed. </p>
			
		</div>
		
	</div>";
		

		$ret .= "<div class='menu'><ul>";
		$uid = $user->getName();
		$canOpenNew = true;

		//ksort($studentReviewItems);
		foreach($studentReviewItems as $key => $ri) {
			$title = "Document # $key ";
			if ($ri->isFinished()) {
				
				if ($this->m->reviewHasFeedback($ri)) {
					$title .= "(has received feedback)";	
				} else {
					$title .= "(complete)";
				}
			} else {
				$title .= " (not complete!)";
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
					$ret .= "<li><a class='menuItem' href='?action=review&New'>Start your first review</a></li>";
				} else {
					$ret .= "<li><a class='menuItem' href='?action=review&New'>Review another document</a></li>";
				}
			} else {
				$ret .= "<li>No more plans to review</li>";	
			}
		} else {
			$ret .= "<li>Complete existing plans to start a new review...</li>";
		}
		$ret .= "</ul></div>";

		$lv->addSection("List of documents to review", $ret);
		
		
		if ($studentReviewItems->isset($index)) {
				$ri = $studentReviewItems->get($index);

				$lv->addSection("Document to review $index", $this->getTestPlanHTML($ri, $index));

				if ($this->m->reviewHasFeedback($ri)) { //We have past the time to submit
					$ret = "<header class=\"major\"><h2>Your saved review</h2></header>";
					$ret .= $this->showReview($ri, " on document # $index ");
					$ret .= "<div class='Warning'>You cannot change a review that has got feedback</div>";
					
				
					$lv->addSection("Your saved review", $ret);
					$lv = $this->showFeedbackOnThisReview($ri, $user, $lv);
				} else {
					$ret = "<h2>Review form</h2>";
					$ret .= $this->getReviewForm($ri, $user, $index);
					$lv->addSection("Review form", $ret);
				}
				
				
		}
		return $lv;
	}

	private function getTestPlanHTML(\model\TestPlanReview $ri, int $index) {

		$ret = "<header class=\"major\"><h2>Document to review # $index</h2></header>";
		$tp = $ri->getTestPlan();
		//$Parsedown = new \Parsedown();
		//$parsed = $Parsedown->text($tp->getContent());
		//
		//
		$parsed = MarkdownExtra::defaultTransform($tp->getContent());
		$ret .= "<div class='testPlan'>$parsed</div>";
		return $ret;
	}

	private function getReviewForm(\model\TestPlanReview $ri, \model\UniqueID $user, int $index) {
		$formContent = "<div class='ReviewForm'>
		";
		if ($ri->isFinished() === false) {
			$formContent .= "<div class='Warning'>You need to complete all fields and give grades on all categories</div>";
		}
		$formContent .= file_get_contents(COURSE_FILES . "/clarityDescription.inc");
		$formContent .= $this->clarityFactorView->getFormContent($ri->getClarity(), "reviewform");
		$formContent .= file_get_contents(COURSE_FILES . "/completenessDescription.inc");
		$formContent .= $this->completenessFactorView->getFormContent($ri->getCompleteness(), "reviewform");
		$formContent .= file_get_contents(COURSE_FILES . "/contentDescription.inc");
		$formContent .= $this->contentFactorView->getFormContent($ri->getContent(), "reviewform");
		$uid = $user->getName();
		

		$ret = "	
					<form  method='post' enctype='multipart/form-data' id='reviewform'  action='?action=review&index=$index'>
				    
				    $formContent
				    </br>
				    <input type='submit' value='Save Review' name='submit'><br/>
					</form>";
		return $ret . "</div>";
	}

	private function showFeedbackOnThisReview(\model\TestPlanReview $ri, \model\UniqueID $reviewer, \view\LayoutView $lv) : \view\LayoutView {
		$ret = "<header class=\"major\"><h2>Feedback on your review</h2></header>";
		$feedbacks = $this->m->getReviewFeedbackList($ri);

		foreach ($feedbacks as $key => $f) {
			$rfv = new ReviewFeedbackView($this->m, $reviewer); 
			
			$ret .= $rfv->getFeedbackHTML($f, "your review from author # $key");
			
		}
		$lv->addSection("Feedback on your review", $ret);

		return $lv;
		
	}
}