<?php

namespace model;


class ReviewFeedbackDAL {

	private $settings;

	public function __construct(\Settings $settings) {

		$this->settings = $settings;
	}

	private function getFolder() {
		
		return $this->settings->getUploadPath() . DIRECTORY_SEPARATOR . "ReviewFeedback";
	}


	public function save(Uniqueid $feedbacker, ReviewFeedback $feedback) {

		$fileName = $this->getFileName($feedbacker, $feedback->getItem());
		

		file_put_contents($fileName, serialize($feedback));
	}

	public function hasFeedbacked(Uniqueid $feedbacker, TestPlanReview $item) : bool {
		$fileName = $this->getFileName($feedbacker, $item);

		return file_exists($fileName);
	}

	public function get(Uniqueid $feedbacker, TestPlanReview $item) : ReviewFeedback {
		$fileName = $this->getFileName($feedbacker, $item);

		$feedback = unserialize(file_get_contents($fileName));

		return $feedback;//new ReviewFeedback($feedback->getFeedbackText(), $feedbacker, $item);
	}

	private function getFileName(Uniqueid $feedbacker, TestPlanReview $item) {
		return $this->getFolder() . DIRECTORY_SEPARATOR . $feedbacker->getName() . "<>" .  
																$item->getReviewer()->getName() . "<>" . 
																$item->getTestPlan()->getMD5();

	}

	

	public function getReviewFeedbackList(TestPlanReview $item) : array {
		$ret = array();
		$filesAndFolders = scandir($this->getFolder());

		foreach($filesAndFolders as $fileOrFolder) {
			if ($fileOrFolder !== "." && $fileOrFolder !== "..") {
				$parts = explode("<>", $fileOrFolder);

				if ($parts[1] === $item->getReviewer()->getName() && $parts[2] === $item->getTestPlan()->getMD5()) {

					$feedback = unserialize(file_get_contents($this->getFolder() . DIRECTORY_SEPARATOR . $fileOrFolder));
					$ret[] = $feedback;
				}
			}
		}

		return $ret;
	}

	public function reviewHasFeedback(TestPlanReview $item) : bool {
		$ret = array();
		$filesAndFolders = scandir($this->getFolder());

		foreach($filesAndFolders as $fileOrFolder) {
			if ($fileOrFolder !== "." && $fileOrFolder !== "..") {
				$parts = explode("<>", $fileOrFolder);

				if ($parts[1] === $item->getReviewer()->getName() && $parts[2] === $item->getTestPlan()->getMD5()) {


					return true;
				}
			}
		}

		return false;
	}
}