<?php

namespace view;

class ViewGradeView {
	public function __construct(\model\StudentModel $m) {
		$this->m = $m;
		

	}

	public function showMustUpload(\view\LayoutView $lv) : \view\LayoutView {
		$lv->addInformation("You must upload a testplan, do reviews and receive feedback to check the grading");

		return $lv;
	}


	public function showHeader(\view\LayoutView $lv) : \view\LayoutView {
		$lv->setHeaderText("Check score","Check how your document was scored by other students and also how your reviews was scored.");
		
		return $lv;
	}

	public function showGrade(\model\UniqueID $student, \view\LayoutView $lv) : \view\LayoutView { 

		if ($this->m->hasPreviouslyUploadedTestPlan($student) == FALSE) {
			$lv->addInformation("You need to upload a document first");
			return $lv;
		}
		$this->plan = $this->m->getTestPlanFromUser($student);

		$this->allReviewsReceived = $this->m->getAllReviews($this->plan);
		$this->allReviewsMade = $this->m->getReviewed($student);
		$grader = new \model\AFGrader();

		$ret = "
				<header class=\"major\"><h2>Your uploaded document's score</h2></header>
				<p>This table gives an overview over how your document was scored by other students. It also shows feedback from you (and your group) and if the teacher has graded the review. 
					Any text that is <span class='strikethrough'>strikethrough</span> indicates that the teacher has either failed the review or the feedback. Those reviews or feedbacks are not counted when you are scored.
				</p>";

		$ret .= "<table>
		<thead>
		<tr>
			<th>Review #</th>
			<th>Clarity</th>
			<th>Completeness</th>
			<th>Content</th>
			<th>Feedback</th>
			<th>Teacher grading of review</th>
		</tr></thead><tbody>";
		$clarityGradings = array();
		foreach($this->allReviewsReceived as $key => $review) {
			$ret .= "<tr>";
			if ($review->isFinished()) {

				$teacherValue = $review->getTeacherFeedback()->getGrading()->getValue();
				if ($teacherValue === \model\Grading::FAILED) {
					$color = "class='strikethrough'";	
				} else {
					$color = "";	
				}

				$clarity = $review->getClarity()->getGrading()->getInterpretableValue();
				$completeness = $review->getCompleteness()->getGrading()->getInterpretableValue();
				$content = $review->getContent()->getGrading()->getInterpretableValue();
				$ret .= "<td>Review # $key</td> 
							<td $color >$clarity</td>
							<td $color >$completeness</td>
							<td $color >$content</td>";
				$ret .= "
						";
				if ($this->m->reviewHasFeedback($review)) { //We have past the time to submit
					$feedbacks = $this->m->getReviewFeedbackList($review);

					$ret .= "<td>
								";
					foreach ($feedbacks as $feedback) {
						$teacherValue = $feedback->getTeacherFeedback()->getGrading()->getValue();
						if ($teacherValue === \model\Grading::FAILED) {
							$color = "class='strikethrough'";	
						} else {
							$color = "";	
						}

						$ret .= "<span $color>" . $feedback->getFeedback()->getGrading()->getInterpretableValue() . "</span></br>";
					}
					$ret .= "</td>";
				} else {
					$ret .= "<td>You should provide feedback on this review</td>";
				}

				$teacherValue = $review->getTeacherFeedback()->getGrading()->getInterpretableValue();
				
				$ret .= "<td>$teacherValue</td>";
				
				
			}
			$ret .= "</tr>";
		}
		if ($this->allReviewsReceived->getCount() == 0) {
			$ret .= "<tr><td>You have not yet got any reviews on your document!</td></tr>";
		} else {
			$score = $grader->getScore($this->allReviewsReceived);
			$readable = $score->getInterpretableValue();
			$grade = $grader->getGradeFromScore($score);
			
			$ret .= "<tr><th colspan='4'>Your Median Score, (note that the final score may change after teacher has reviewed)</th><td>$readable</td><td>$grade</td></tr>";
			
		}
		

		$ret .= "</tbody></table>";

		$lv->addSection("Your uploaded document score", $ret);

		
		$ret = "<header class=\"major\"><h2>Your review score</h2></header>
		<p>This table gives an overview over how your reviews were received by the authors of those documents and if the teacher has graded the review or feedback. 
					Any text that is <span class='strikethrough'>strikethrough</span> indicates that the teacher has either failed the review or the feedback. Those reviews or feedbacks are not counted when you are scored.
		</p>";

		$ret .= "<table>
					<thead>
						<tr>
						<th>Your Review #</th>
						<th>Feedback on the review from the authors of the document</th>
						<th>Teacher grading of Review</th>
						</tr>
					</thead>
					<tbody>";



		foreach($this->allReviewsMade as $key => $review) {

			$teacherValue = $review->getTeacherFeedback()->getGrading()->getValue();
				if ($teacherValue === \model\Grading::FAILED) {
					$color = "class='strikethrough'";	
				} else {
					$color = "";	
				}

			$ret .= "<tr><td $color>Your review # $key</td>";
			$ret .= "<td>";
			if ($review->isFinished()) {
				if ($this->m->reviewHasFeedback($review)) { //We have past the time to submit
					$feedbacks = $this->m->getReviewFeedbackList($review);

					
					foreach ($feedbacks as $feedback) {

						$teacherValue = $feedback->getTeacherFeedback()->getGrading()->getValue();
						if ($teacherValue === \model\Grading::FAILED) {
							$color = "class='strikethrough'";	
						} else {
							$color = "";	
						}

						$ret .= "<span $color>" . $feedback->getFeedback()->getGrading()->getInterpretableValue() . "</span></br>";
					}
					
				} else {
					$ret .= "The authors of the document have not yet given feedback on this review";	
				}
			} else {
				$ret .= "The review is not complete, you should finish it before it can get feedback";	
			}
			$ret .= "</td>";
			$teacherValue = $review->getTeacherFeedback()->getGrading()->getInterpretableValue();
				
				$ret .= "<td>$teacherValue</td>";
			$ret .= "</tr>";


		}
		
		$bestReviewsScores = $grader->getReviewScore($this->allReviewsMade, $this->m);
		$ret .= "<tr><th colspan='2'>Your reviewer score(note that the final score may change after teacher has reviewed)</th><td>$bestReviewsScores</td></tr>";			

		$ret .= "</tbody></table>";

		$lv->addSection("Your reviewer score", $ret);

		return $lv;
	}
}