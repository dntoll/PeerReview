<?php

namespace view;

class ViewGradeView {
	public function __construct(\model\StudentModel $m) {
		$this->m = $m;


	}

	public function showMustUpload(\view\LayoutView $lv) : \view\LayoutView {
		include("./language.php");
		$lv->addInformation($lang[LANGUAGE]['grading']['must_upload']);

		return $lv;
	}


	public function showHeader(\view\LayoutView $lv) : \view\LayoutView {
		include("./language.php");
		$lv->setHeaderText($lang[LANGUAGE]['headings']['score_top_heading'],$lang[LANGUAGE]['headings']['score_sub_heading']);

		return $lv;
	}

	public function showGrade(\model\UniqueID $student, \view\LayoutView $lv) : \view\LayoutView {
		include("./language.php");
		if ($this->m->hasPreviouslyUploadedTestPlan($student) == FALSE) {
			$lv->addInformation($lang[LANGUAGE]['headings']['score_top_heading'],$lang[LANGUAGE]['grading']['no_document']);
			return $lv;
		}
		$this->plan = $this->m->getTestPlanFromUser($student);

		$this->allReviewsReceived = $this->m->getAllReviews($this->plan);
		$this->allReviewsMade = $this->m->getReviewed($student);
		$grader = new \model\AFGrader();

		$ret = "
				<header class=\"major\"><h2>".$lang[LANGUAGE]['grading']['page_heading_1']."</h2></header>
				<p>".$lang[LANGUAGE]['grading']['page_paragraph_1']."</p>";

		$ret .= "<table>
		<thead>
		<tr>
			<th>".$lang[LANGUAGE]['grading']['table_heading_review_nr']."</th>
			<th>".$lang[LANGUAGE]['grading']['table_heading_clarity']."</th>
			<th>".$lang[LANGUAGE]['grading']['table_heading_completeness']."</th>
			<th>".$lang[LANGUAGE]['grading']['table_heading_content']."</th>
			<th>".$lang[LANGUAGE]['grading']['table_heading_feedback']."</th>
			<th>".$lang[LANGUAGE]['grading']['table_heading_teacher_grading']."</th>
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
					$ret .= "<td>".$lang[LANGUAGE]['grading']['should_provide_feedback']."</td>";
				}

				$teacherValue = $review->getTeacherFeedback()->getGrading()->getInterpretableValue();

				$ret .= "<td>$teacherValue</td>";


			}
			$ret .= "</tr>";
		}
		if ($this->allReviewsReceived->getCount() == 0) {
			$ret .= "<tr><td>".$lang[LANGUAGE]['grading']['no_received_reviews']."</td></tr>";
		} else {
			$score = $grader->getScore($this->allReviewsReceived);
			$readable = $score->getInterpretableValue();
			$grade = $grader->getGradeFromScore($score);

			$ret .= "<tr><th colspan='4'>".$lang[LANGUAGE]['grading']['median_score']."</th><td>$readable</td><td>$grade</td></tr>";

		}


		$ret .= "</tbody></table>";

		$lv->addSection($lang[LANGUAGE]['navigation']['score_section_document'], $ret);


		$ret = "<header class=\"major\"><h2>".$lang[LANGUAGE]['grading']['page_heading_2']."</h2></header>
		<p>".$lang[LANGUAGE]['grading']['page_paragraph_2']."</p>";

		$ret .= "<table>
					<thead>
						<tr>
						<th>".$lang[LANGUAGE]['grading']['review_table_heading_nr']."</th>
						<th>".$lang[LANGUAGE]['grading']['review_table_heading_review_feedback']."</th>
						<th>".$lang[LANGUAGE]['grading']['review_table_heading_teacher_grading']."</th>
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

			$ret .= "<tr><td $color>".$lang[LANGUAGE]['grading']['review_table_your_review']." # $key</td>";
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
					$ret .= $lang[LANGUAGE]['grading']['review_table_no_feedback'];
				}
			} else {
				$ret .= $lang[LANGUAGE]['grading']['review_table_review_not_complete'];
			}
			$ret .= "</td>";
			$teacherValue = $review->getTeacherFeedback()->getGrading()->getInterpretableValue();

				$ret .= "<td>$teacherValue</td>";
			$ret .= "</tr>";


		}

		$bestReviewsScores = $grader->getReviewScore($this->allReviewsMade, $this->m);
		$ret .= "<tr><th colspan='2'>".$lang[LANGUAGE]['grading']['review_table_reviewer_score']."</th><td>$bestReviewsScores</td></tr>";

		$ret .= "</tbody></table>";

		$lv->addSection($lang[LANGUAGE]['navigation']['score_section_review'], $ret);

		return $lv;
	}
}
