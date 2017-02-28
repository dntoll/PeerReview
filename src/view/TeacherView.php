<?php

namespace view;

class TeacherView {

	private static $TEACHER_REVIEW_DOCUMENT = "TeacherReviewDocument";


	public function __construct(\model\StudentModel $m, StudentView $sv, \Settings $s) {
		$this->m = $m;
		$this->sv = $sv;
		$this->uv = new UploadView($s);
		$this->settings = $s;
		$this->rv = new ReviewView($this->m);


		$gradeTitles = require(COURSE_FILES . INFORMATION_TEXT . "/reviewGrades.inc");
		$this->teacherReviewView = new ReviewFactorView("review", $gradeTitles);



		for ($i = 0; $i < 16; $i++) {
			$this->teacherFeedbackViews[$i] = new ReviewFactorView("feedbackView$i", array(-1 => "not checked", 1 => "fail", 2 => "ok"));
		}


		if (isset($_GET["st"])) {
			$this->studentReviewToShowMD5 = $_GET["st"];
		} else {
			$this->studentReviewToShowMD5 = NULL;
		}
		if (isset($_GET["tp"])) {
			$this->planToShowMD5 = $_GET["tp"];
		} else {
			$this->planToShowMD5 = NULL;
		}
	}


	public function teacherReviewsReview() : bool{
		return isset($_POST["submitReview"]);;
	}

	public function teacherReviewsFeedback() : bool{
		return isset($_POST["submitFeedback"]);;
	}

	public function teacherReviewsTestPlan() : bool {
		return isset($_GET[self::$TEACHER_REVIEW_DOCUMENT]);
	}

	public function getTestPlan() : \model\TestPlan {
		return $this->m->getTestPlanFromMD5($this->planToShowMD5);
	}



	public static function isTryingToGetTeacherAccess() : bool {
		return isset($_GET["teacher"]);
	}


/**
 * Adapted from Victor T.'s answer
 * http://codereview.stackexchange.com/questions/220/calculate-a-median
 */
private function array_median($array) {
  // perhaps all non numeric values should filtered out of $array here?
  $iCount = count($array);
  if ($iCount == 0) {
    //throw new DomainException('Median of an empty array is undefined');
    return 0;
  }
  // if we're down here it must mean $array
  // has at least 1 item in the array.
  $middle_index = floor($iCount / 2);
  sort($array, SORT_NUMERIC);
  $median = $array[$middle_index]; // assume an odd # of items
  // Handle the even case by averaging the middle 2 items
  if ($iCount % 2 == 0) {
    $median = ($median + $array[$middle_index - 1]) / 2;
  }
  return $median;
}

	public function show(\model\UniqueID $user, \view\LayoutView $lv) : \view\LayoutView {
		$ret ="";

		$students = $this->m->getAllStudents();
		$testPlans = $this->m->getAllTestPlans();


		asort($students);


		$maxReviews = 0;
		$report = array();
		foreach($testPlans as $testPlanMD5 => $testPlan) {
			$authors = array();

			foreach($students as $studentID => $studentTestPlanMD5) {
				if($studentTestPlanMD5 === $testPlanMD5) {
					$authors[] = $studentID;
				}
			}

			$reviews = $this->m->getAllReviews($testPlan);

			if ($reviews->getCount() > $maxReviews) {
				$maxReviews = $reviews->getCount();
			}

			if ($this->planToShowMD5 === $testPlanMD5) {
				$planToShow = $testPlan;
				foreach($reviews as $index => $review) {
					if ($review->isFinished()) {
						if ($review->getReviewer()->getName() === $this->studentReviewToShowMD5) {

							$reviewToShow = $review;

							if ($this->m->reviewHasFeedback($review)) { //We have past the time to submit
								$feedbacks = $this->m->getReviewFeedbackList($review);
								$feedbacksToShow = $feedbacks;
							}
						}
					}
				}
			}

			$report[$testPlanMD5] = array("Authors" => $authors, "Reviews" => $reviews);
		}


		$ret .= "<table>
					<thead>
						<tr>
							<th>Test Plan</th>
							<th># Authors</th>
							<th>Median Score</th>
							<th>Factor</th>
							<th>min_med_max</th>";

		for ($i = 0; $i< $maxReviews ; $i++) {
			$ret .= 	   "<th>$i</th>";
			$ret .= 	   "<th>time </th>";
		}
		$ret .= 		"</tr>
					</thead>";
		$ret .= "<tbody>";

		$index = 0;
		$grader = new \model\AFGrader();
		foreach($testPlans as $testPlanMD5 => $testPlan) {

			//TODO Move this to a model class, TestPlan?
			$clarities = array();
			$completeness = array();
			$contents = array();
			$teacherHasReviewed = false;
			$viewingTeacherHasReviewed = false;
			$viewingTeacherReviewIndex = 0;

			foreach($report[$testPlanMD5]["Reviews"] as $key => $review) {
				//calculate the scoring
				if ($review->getClarity()->getGrading()->getValue() != \model\Grading::NOT_GRADED)
					$clarities[] = $review->getClarity()->getGrading()->getValue();
				if ($review->getCompleteness()->getGrading()->getValue() != \model\Grading::NOT_GRADED)
					$completeness[] = $review->getCompleteness()->getGrading()->getValue();
				if ($review->getContent()->getGrading()->getValue() != \model\Grading::NOT_GRADED)
					$contents[] = $review->getContent()->getGrading()->getValue();


				//check if a teacher has reviewed this plan
				if ($this->settings->isTeacher($review->getReviewer())) {
					$teacherHasReviewed = true;
				}
				if ($review->getReviewer()->getName() == $user->getName()) {
					$viewingTeacherHasReviewed = true;
					$viewingTeacherReviewIndex = $review->getIndex();
				}

			}


			$minc = @min($clarities);
			$medc = $this->array_median($clarities);
			$maxc = @max($clarities);

			$mincom = @min($completeness);
			$medcom = $this->array_median($completeness);
			$maxcom = @max($completeness);

			$mincon = @min($contents);
			$medcon = $this->array_median($contents);
			$maxcon = @max($contents);
			//TODO move until here


			$addReviewURL = "?teacher&tp=$testPlanMD5&" . self::$TEACHER_REVIEW_DOCUMENT ;


			if ($teacherHasReviewed) {
				$color = "class='checked'";
			} else {
				$color = "";
			}

			if ($viewingTeacherHasReviewed) {
				$addReviewLink = "<a href='?action=review&index=$viewingTeacherReviewIndex#Document+to+review+$viewingTeacherReviewIndex'>Work on your Teacher Review</a>";
			} else {
				$addReviewLink = "<a href='$addReviewURL'>Add Teacher Review</a>";
			}

			$ret .= "<tr><td $color>$testPlanMD5 $addReviewLink  </td>";
			$ret .= "<td>" . count($report[$testPlanMD5]["Authors"]) ."</td>";


			$score = $grader->getScore($report[$testPlanMD5]["Reviews"])->getInterpretableValue();

			$ret .= "<td>$score</td>";







			$ret .= "<td>Cla<br/>Com<br/>Con<br/></td>
					<td>$minc->$medc->$maxc<br/>
						$mincom->$medcom->$maxcom<br/>
						$mincon->$medcon->$maxcon</td>";

			foreach($report[$testPlanMD5]["Reviews"] as $key => $review) {
				$ret .= "<td>";

				$ret .= $this->showReview($review, $testPlanMD5);



				$ret .= "</td>";
				$ret .= "<td>";
				$ret .= $review->getTotalTimeMinutes() . " min";
				$ret .= "</td>";
			}




			$index++;
			$ret .= "</tr>";



		}
		$ret .= "</tbody></table>";

		$lv->addSection("Documents", $ret);


		$ret = "<table>
					<thead>
						<tr>
							<th>ix</th>
							<th>Student</th>
							<th>StudentID</th>
							<th>PlanID</th>
							<th>Most Similar</th>
							<th>Document Grade</th>
							<th>Reviewer Grade</th>
							<th>Number of Reviews made</th>
							<th>Count</th>
							<th>Number of Reviews received</th>
							<th>Count</th>
							<th>Has unanswered feedback</th>";
		$ret .= 		"</tr>
					</thead>";
		$ret .= "<tbody>";

		$index = 0;
		foreach($students as $studentID => $studentTestPlanMD5) {
			$student = new \model\UniqueID($studentID);
			$plan = $this->m->getTestPlanFromUser($student);
			$allReviewsMade = $this->m->getReviewed($student);

			$allReviewsReceived = $this->m->getAllReviews($plan);
			$numReviewsMade = $allReviewsMade->getFinishedCount();
			$numReviewsReceived = $allReviewsReceived->getFinishedCount();
			$numReviewsReceivedWords = $allReviewsReceived->getTextCount();
			$numReviewsMadeWords = $allReviewsMade->getTextCount();


			//check if the student has responded to the review
			$shouldRespondToReviews = "";
			foreach ($allReviewsReceived as $reviewReceived) {
				if ($reviewReceived->isFinished()) {
					if ($this->m->hasFeedbacked($student, $reviewReceived) == false) {
						$shouldRespondToReviews .= "email!!!";
					}
				}
			}

			$score = $grader->getScore($allReviewsReceived);
			$documentGrade = $grader->getGradeFromScore($score);

			$reviewGrade = $grader->getReviewScore($allReviewsMade, $this->m);
			$ret .= "<tr>
						<td>$index</td>
						<td>" . $this->m->getStudentRealID($student)."</td>
						<td>$studentID</td>
						<td><a href='?teacher&tp=" . $plan->getMD5(). "#The+uploaded+document'>" . $plan->getMD5() . " </a></td>";
			$index++;

			//COMPARE TP's WARNING TAKES TOO MUCH TIME, SHOULD BE DONE OFFLINE?
			if (isset($_GET["cmp"])) {
				/*$ret .= "<td>";
				$highestPercent = 0;
				$mostSimiliarTP = $plan->getMD5();
				foreach($testPlans as $testPlanMD52 => $testPlan2) {
					if ($plan->getMD5() !== $testPlanMD52) {

						similar_text($plan->getContent(), $testPlan2->getContent(), $percent);
						$percent = round($percent, 1);

						if ($percent > $highestPercent) {
							$highestPercent = $percent;
							$mostSimiliarTP = $testPlan2->getMD5();
						}
					}
				}
				$ret .= "$mostSimiliarTP $highestPercent %";
				$ret .= "</td>";
				*/
			} else {
				$ret .= "<td></td>";
			}

			$ret .= "	<td>$documentGrade</td>
						<td>$reviewGrade</td>
						<td>$numReviewsMade</td>
						<td>$numReviewsMadeWords</td>
						<td>$numReviewsReceived</td>
						<td>$numReviewsReceivedWords</td>
						<td>$shouldRespondToReviews</td>";

			$ret .= "</tr>";

		}

		$ret .= "</tbody></table>";

		$lv->addSection("Students", $ret);

		$ret = "";
		$uid = $user->getName();
		if (isset($planToShow)) {

			$lv = $this->uv->showTestPlan($planToShow, $lv);



			if (isset($reviewToShow)) {
				$ret .= "<h2>Review by reviewing student : ".$reviewToShow->getReviewer()->getName()."</h2>";
				$ret .= $this->rv->showReview($reviewToShow);
				$lv->addSection("Review", $ret);

				$teacherFeedback = $reviewToShow->getTeacherFeedback();

				$ret = "
				<h2>Teacher Review of the review</h2>
				<form  method='post' enctype='multipart/form-data' id='teacherFeedReview'  action='?teacher&st=$this->studentReviewToShowMD5&tp=$this->planToShowMD5'>
					    <input type='submit' value='Save Review' name='submitReview'><br/>";
				$ret .= $this->teacherReviewView->getFormContent($teacherFeedback, "teacherFeedReview");
				$ret .= "</form>";
				$lv->addSection("Teacher feedback on Review", $ret);


				if (isset($feedbacksToShow)) {

					foreach ($feedbacksToShow as $key => $feedback) {
						$ret = "<h2>Feedback from the authors " . $feedback->getFeedbacker()->getName() ."</h2>";
						$rfv = new \view\ReviewFeedbackView($this->m, $feedback->getFeedbacker());
						$ret .= $rfv->getFeedbackHTML($feedback, "");

						$fb = $feedback->getTeacherFeedback();

						$ret .= "
								<h2>Teacher Review of the feedback</h2>
								<form  method='post' enctype='multipart/form-data' id='teacherFeedback$key'  action='?teacher&st=$this->studentReviewToShowMD5&tp=$this->planToShowMD5&feedbackindex=$key'>
									    <br/>";
								$ret .= $this->teacherFeedbackViews[$key]->getFormContent($fb, "teacherFeedback$key");
								$ret .= "<input type='submit' value='Save Feedback' name='submitFeedback'></form>";
						$lv->addSection("Teacher Feedback on Feedback", $ret);
					}
				}
			}
		}




		return $lv;
	}

	public function getReviewFeedback() : \model\ReviewFactor {
		return $this->teacherReviewView->getPostedFactor();
	}

	public function getFeedbackFeedback() : \model\ReviewFactor {
		$feedbackView = $this->teacherFeedbackViews[$this->getFeedbackIndex()];
		return $feedbackView->getPostedFactor();
	}

	public function getReview() : \model\TestPlanReview {

		$reviews = $this->m->getReviewed(new \model\UniqueID($this->studentReviewToShowMD5));
		foreach($reviews as $index => $review) {
			if ($review->getTestPlan()->getMD5() === $this->planToShowMD5) {
				return $review;
			}
		}
		throw new \Exception("Unable to find review from this student on this plan!");
	}

	public function getFeedbackIndex() : int {
		return $_GET["feedbackindex"];
	}

	public function getFeedback() : \model\ReviewFeedback {
		$review = $this->getReview();
		if ($this->m->reviewHasFeedback($review)) { //We have past the time to submit
			$feedbacks = $this->m->getReviewFeedbackList($review);
			return $feedbacks[$this->getFeedbackIndex()];
		}
		throw new \Exception("Unable to find feedback from this student on this plan!");
	}



	private function getColoredGrading(\model\Grading $g, int $span, bool $teacherHasChecked, bool $failedReview) {
		$color  = 0;

		if($g->getValue() == \model\Grading::NOT_GRADED) {
			$color = "class='not_graded'";
		}
		if($teacherHasChecked == TRUE) {
			$color = "class='checked'";
		}
		if($teacherHasChecked == FALSE && $g->getValue() < 2 && $g->getValue() >= 0) {
			$color = "class='attention'";
		}
		if ($failedReview) {
			$color = "class='strikethrough'";
		}


		return  "<td colspan=\"$span\" $color>" . $g->getInterpretableValue() . "</td>";

	}

	private function showReview(\model\TestPlanReview $review, string $testPlanMD5) {

		$ret = "<table>";

		if ($this->settings->isTeacher($review->getReviewer())) {
			$ret .= "<tr>
						<th colspan='3' class='checked'>Teacher Review</th>
					</tr>";
		}






		$ret .= "<tr>
					<th>Cla:</th><th>Com:</th><th>Con:</th>
				</tr>";

		$teacherHasChecked = $review->getTeacherFeedback()->getGrading()->isFinished();

		if ($review->getTeacherFeedback()->getGrading()->getValue() == \model\Grading::FAILED) {
			$failedReview = true;
		} else {
			$failedReview = false;
		}

		$ret .= $this->getColoredGrading($review->getClarity()->getGrading(), 1, $teacherHasChecked, $failedReview);
		$ret .= $this->getColoredGrading($review->getCompleteness()->getGrading(), 1, $teacherHasChecked, $failedReview);
		$ret .= $this->getColoredGrading($review->getContent()->getGrading(), 1, $teacherHasChecked, $failedReview);

		$feedbacks = $this->m->getReviewFeedbackList($review);



		foreach ($feedbacks as $feedback) {
			$ret .= "<tr><th>Feed:</th>";
			$teacherFeed = $feedback->getTeacherFeedback();


			$teacherHasCheckedFeed = $teacherFeed->getGrading()->isFinished();
			if ($teacherFeed->getGrading()->getValue() == \model\Grading::FAILED) {
				$failedFeed = true;
			} else {
				$failedFeed = false;
			}

			$ret .= $this->getColoredGrading($feedback->getFeedback()->getGrading(), 2, $teacherHasCheckedFeed, $failedFeed);


			$ret .= "</tr>";
		}

		//output teacher feedback
		if ($teacherHasChecked) {
			$ret .= "<tr><th>Teacher:</th>" . $this->getColoredGrading($review->getTeacherFeedback()->getGrading(), 2, true, false);
		}

		 $studentID = $review->getReviewer()->getName();
		$ret .= "<tr><td colspan=\"3\"><a href='index.php?teacher&st=$studentID&tp=$testPlanMD5#Review'>Check";
			$ret .= "</a></td></tr>";

		$ret .= "</table>";
		return $ret;
	}

}
