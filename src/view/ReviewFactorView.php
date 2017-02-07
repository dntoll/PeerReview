<?php

namespace view;

use \Michelf\MarkdownExtra;


class ReviewFactorView {
	private $postIndex;

	public function __construct(string $postIndex, array $reviewFactorGradeTitles) {
		$this->postIndex = $postIndex;
		$this->gv = new \view\GradingView($this->postIndex . "_grading");


		$this->reviewFactorGradeTitles = $reviewFactorGradeTitles;

	}

	public function getPostedFactor() : \model\ReviewFactor{

		$feedbackText = htmlentities($_POST[$this->postIndex], ENT_QUOTES);
		return new \model\ReviewFactor($feedbackText,  $this->gv->getPostedGrading());
	}

	public function getFormContent(\model\ReviewFactor $feedback, string $formName) {

		$oldFeedBackText = $feedback->getText();
		$grading = $feedback->getGrading();
		
		
		$gradingViewFormHTML = $this->gv->getForm($grading, $this->reviewFactorGradeTitles);

		return "
		<textarea rows='6' cols='100' name='$this->postIndex' form='$formName'>$oldFeedBackText</textarea>
    	<br/>
    	$gradingViewFormHTML";
	}

	public function getHTMLContent(\model\ReviewFactor $feedback, string $title) {
		//$Parsedown = new \Parsedown();
	
		//$parsed = $Parsedown->text($feedback->getText());
		//TODO: Make sure html does not slip through, this is open right now. 
		$decoded = html_entity_decode($feedback->getText());
		//$decoded = htmlspecialchars($decoded, ENT_HTML5 | ENT_NOQUOTES | ENT_SUBSTITUTE);

		$parsed = MarkdownExtra::defaultTransform($decoded);


		$gv = new \view\GradingView("");
		$ret = "<div class='Review'>";
		$ret .= "<h3>Comment on $title</h3>";
		$ret .= "<div class='ReviewText'>$parsed</div>";
		$ret .= "<h3>Grade</h3>";
		$ret .= "<div>" . $gv->getGradeHTML($feedback->getGrading(), $this->reviewFactorGradeTitles) . "</div>";
		$ret .= "</div>";

		return $ret;
	}
}