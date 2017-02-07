<?php

namespace controller;


require_once("src/view/ReviewFeedbackView.php");


class ReviewFeedbackController {
	

	public function __construct(\view\StudentView $v, \model\StudentModel $m, \Settings $s) {
		$this->v = $v;
		$this->m = $m;
		$this->s = $s;
	}

	public function doControl(\view\ReviewFeedbackView $rv, \model\UniqueID $uid,  \view\LayoutView $lv) : \view\LayoutView {

		if ($this->m->studentShouldUpload($uid)) {
			return $this->v->showStudentNeedsToUploadFirst($lv);	
		}

		if ($this->m->studentShouldReviewReviews($uid ) ) {

			

			try {
				$studentReviewFactor = $rv->getActiveReview();

				if ($rv->userReviewsReview()) {
					$this->m->saveReviewRFeedback($uid, $rv->getReviewFeedback());
				}
			
			
				$lv = $rv->viewReview(new \view\ReviewView($this->m), $lv);
			} catch (\Exception $e){
				$lv = $this->v->noReviewsRecievedYetNotice($lv);
			}
		} else {
			$lv = $this->v->notTimeToGiveFeedbackNotice($lv);
		}
		return $lv;
	}
}