<?php

/**
* You will find the settings that can be edited
* in the course/code/settings.inc
* this is the ob
*/

class Settings {

	private $uploadTime;
	private $reviewTime;
	private $feedbackTime;



	public function __construct() {
		$this->reviewSourceType = REVIEW_SOURCE_TYPE;
		$this->uploadDeadline = END_OF_UPLOAD_PHASE;
		$this->feedbackStarts = END_OF_REVIEW_PHASE;
	}

	public function getDeadlineTimeString(): string {
		return date("F j, Y, g:i a", $this->uploadDeadline);
	}

	public function getFeedbackDeadlineTimeString(): string {
		return date("F j, Y, g:i a", $this->feedbackStarts);
	}

	public function getUploadPath() : string {
		return UPLOAD_PATH;
	}

	public function isTimeToReview() : bool {
		return time() > $this->uploadDeadline;
	}

	public function isTimeForFeedback() : bool {
		return time() > $this->feedbackStarts;
	}

	public function getSalted(string $userName) : string {
		return md5( $userName . COURSE_PRESS_SALT_USERID ); //from wp_config dependency to coursepress
	}

	public function isTeacher(\model\UniqueID $id) {
		$ts = TEACHERS;
		foreach ($ts as $name) {
			if ($id->getName() === $this->getSalted($name) ) {
				return true;
			}
		}

		return false;
	}

	public function getStudentFile() : string {
		return STUDENT_FILE;
	}
}
