<?php

namespace model;




class ReviewDAL {


	private $settings;

	public function __construct(\Settings $settings) {

		$this->settings = $settings;
	}

	private function getReviewFolder() {
		
		return $this->settings->getUploadPath() . DIRECTORY_SEPARATOR . "Review";
	}


	public function saveReview(TestPlanReview $item, int $index) {
		
		$itemText = serialize($item);

		file_put_contents($this->getReviewFolder() . DIRECTORY_SEPARATOR . $item->getReviewer()->getName() . "<>" . $item->getTestPlan()->getMD5() . "<>" . $index, $itemText );

	}


	public function getAllReviewsByUser(Uniqueid $uid, TestPlanDAL $tpd) : TestPlanReviewList {
		$ret = new TestPlanReviewList();
		$filesAndFolders = scandir($this->getReviewFolder());

		foreach($filesAndFolders as $fileOrFolder) {
			if ($fileOrFolder !== "." && $fileOrFolder !== "..") {
				$parts = explode("<>", $fileOrFolder);

				if ($parts[0] === $uid->getName()) {
					$review = unserialize(file_get_contents($this->getReviewFolder() . DIRECTORY_SEPARATOR . $fileOrFolder));
					//$review = new ReviewFactor();
					//$testPlan = $tpd->getTestPlan($parts[1]);
					//$ri = $review;//new TestPlanReview($testPlan, $uid, $review);
					$review->setIndex($parts[2]);
					$ret->add($review, $parts[2]);
				}
			}
		}

		return $ret;
	}

	public function getAllReviewsForPlan(TestPlan $plan, TestPlanDAL $tpd) : TestPlanReviewList {
		$ret = new TestPlanReviewList();
		$filesAndFolders = scandir($this->getReviewFolder());

		$index = 0;
		foreach($filesAndFolders as $fileOrFolder) {
			if ($fileOrFolder !== "." && $fileOrFolder !== "..") {
				$parts = explode("<>", $fileOrFolder);

				if ($parts[1] === $plan->getMD5()) {
					$review = unserialize(file_get_contents($this->getReviewFolder() . DIRECTORY_SEPARATOR . $fileOrFolder));
					
					//$ri = $review;//new TestPlanReview($testPlan, $uid, $review);
					$review->setIndex($parts[2]);
					$ret->add($review, $index);
					$index++;
				}
			}
		}

		return $ret;

	}

	public function getTestPlansReviewedByUser(Uniqueid $uid, TestPlanDAL $tpd) : TestPlanList {
		$ri = $this->getAllReviewsByUser($uid, $tpd);

		$ret = new TestPlanList();

		foreach($ri as $r) {
			$ret->add($r->getTestPlan());
		}

		return $ret;
	}

	

	public function getNumReviews(TestPlan $plan) : int{
		$ret = 0;
		$filesAndFolders = scandir($this->getReviewFolder());

		foreach($filesAndFolders as $fileOrFolder) {
			if ($fileOrFolder !== "." && $fileOrFolder !== "..") {
				$parts = explode("<>", $fileOrFolder);

				if ($parts[1] === $plan->getMD5()) {
					//load the review and check if the review is complete...
					//WARNING: This resulted in a lot of reviews on a single plan
					//$review = unserialize(file_get_contents($this->getReviewFolder() . DIRECTORY_SEPARATOR . $fileOrFolder));

					//if ($review->isFinished()) {
						$ret++;
					//}
				}
			}
		}

		return $ret;
	}

	public function getTPWithLeastReviews(TestPlanList $allTPs) : TestPlanList {
		$minimumReviews = PHP_INT_MAX;

		$ret = new TestPlanList();

		foreach ($allTPs->get() as $plan) {

			$num = $this->getNumReviews($plan);

			if ($num < $minimumReviews) {
				$minimumReviews = $num;
				$ret = new TestPlanList();
				$ret->add($plan);
			} else if ($num < $minimumReviews) {
				$ret->add($plan);
			}
		}
		return $ret;
	}
}