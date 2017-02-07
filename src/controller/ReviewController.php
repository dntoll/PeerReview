<?php

namespace controller;


require_once("src/view/ReviewView.php");


class ReviewController {
	

	public function __construct(\view\ReviewView $rv, \view\StudentView $v, \model\StudentModel $m, \Settings $s) {
		$this->v = $v;
		$this->m = $m;
		$this->rv = $rv;
		$this->settings = $s;
	}

	public function doControl(\model\UniqueID $uid, \view\LayoutView $lv) : \view\LayoutView{

		if ($this->settings->isTimeToReview() === FALSE ) {
			
			return $this->v->showNotTimeForReviews($lv);
		}
		
		if ($this->m->studentShouldReview($uid) || $this->settings->isTeacher($uid)) {


			try {
				$allReviewsMade = $this->m->getReviewed($uid);
				$index = $this->rv->getActiveReviewIndex();
				
				if ($this->rv->studentSubmitsReview()) {
					$ri = $allReviewsMade->get($index);
					$ri = $this->rv->getSubmittedReview($ri);

					if ($this->m->reviewHasFeedback($ri) == FALSE) { //We have past the time to submit
						$this->m->saveReview($ri, $index);
					}
				}

				if ($allReviewsMade->areFinished() && $this->rv->studentsCreatesNewReview()) {
					$index = $this->m->newReview($uid);
					$allReviewsMade = $this->m->getReviewed($uid);
				}
				
				$lv = $this->rv->showReviewForm($allReviewsMade, $uid, $index, $lv);
			} catch (\Exception $e) {
				$lv = $this->v->showNoAvailableTestPlans($lv);
				$lv = $this->rv->showReviewForm($allReviewsMade, $uid, $index, $lv);
			}
		} else {

			if ($this->m->studentShouldUpload($uid)) {
				$lv = $this->v->showStudentNeedsToUploadFirst($lv);	
			}

			if ($this->settings->isTimeForFeedback() === TRUE ) {
				$lv = $this->v->showStudentShouldDoFeedbackNow($lv);	
			}

			
		}
		return $lv;
	}
}