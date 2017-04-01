<?php

namespace model;


require_once("TestPlan.php");
require_once("DAL/TestPlanDAL.php");
require_once("TestPlanList.php");
require_once("ReviewFactor.php");
require_once("DAL/ReviewDAL.php");
require_once("DAL/StudentDAL.php");
require_once("TestPlanReview.php");
require_once("TestPlanReviewList.php");
require_once("Grading.php");
require_once("ReviewFeedback.php");
require_once("DAL/ReviewFeedbackDAL.php");



/**
 * This is the facade used by all the controllers
 * Single point of mess
 */
class StudentModel {
	private $settings;
	private $testPlanDAL;

	public function __construct(\Settings $settings) {

		$this->testPlanDAL = new TestPlanDAL($settings);
		$this->reviewDAL = new ReviewDAL($settings);
		$this->reviewFeedbackDAL = new ReviewFeedbackDAL($settings);
		$this->studentDAL = new StudentDAL($settings);
		$this->settings = $settings;
		$this->language = \Language::getLang();
	}

	public function hasPreviouslyUploadedTestPlan(UniqueID $student) : bool {
		return $this->studentDAL->hasPreviouslyUploadedTestPlan($student);
	}

	public function getAllStudents() : array {
		return $this->studentDAL->getAllStudents();
	}

	public function isStudent(UniqueID $unknown) {
		$students = explode("\n", file_get_contents($this->settings->getStudentFile()));
		foreach($students as $name) {
			if ($this->settings->getSalted(trim($name)) === $unknown->getName()) {
				return true;
			}
		}
		return false;
	}

	public function getStudentRealID(UniqueID $unknown) {
		$students = explode("\n", file_get_contents($this->settings->getStudentFile()));
		foreach($students as $name) {
			if ($this->settings->getSalted(trim($name)) === $unknown->getName()) {
				return $name;
			}
		}
		return false;
	}

	public function getTestPlanFromUser(UniqueID $student) : TestPlan {
		return $this->studentDAL->getTestPlanFromUser($student, $this->testPlanDAL);
	}

	public function getTestPlanFromMD5(string $md5) : TestPlan {
		return $this->testPlanDAL->getTestPlan($md5);
	}

	public function doSaveTestPlan(UploadedFile $file, UniqueID $student) {
		$this->studentDAL->saveUserTestPlan($file, $student);
		$this->testPlanDAL->doSaveTestPlan($file, $student);
	}

	/*public function doSaveReview(UploadedFile $file, UniqueID $student, TestPlanReview $item) {
		$dest = $this->getReviewFileName($student);
		$file->move($dest);
	}*/



	public function studentShouldUpload(UniqueID $student) : bool {

		if ($this->studentDAL->hasPreviouslyUploadedTestPlan($student) === FALSE ) {
			return true;
		}

		//You may not change this beyond this point
		if ($this->settings->isTimeToReview() === FALSE ) {
			return true;
		}
		return false;
	}

	public function studentShouldReview(UniqueID $student) : bool {

		if ($this->studentDAL->hasPreviouslyUploadedTestPlan($student) === FALSE ) {
			return false;	//not uploaded
		}


		if ($this->settings->isTimeToReview() === FALSE ) { //We have past the time to submit
			return false;
		}
		return true;
	}

	public function studentShouldReviewReviews(UniqueID $student) : bool {
		if ($this->settings->isTimeForFeedback() === TRUE ) { //We have past the time to submit
			return true;
		}
		return false;
	}

	public function saveReview(TestPlanReview $item, int $index) {
		$item->setIndex($index);
		$this->reviewDAL->saveReview($item, $index);

	}

	public function saveTeacherReview(TestPlanReview $r, ReviewFactor $teachersReview) {
		$r->setTeachersComment($teachersReview);

		$this->saveReview($r, $r->getIndex());

	}

	public function saveTeacherFeedback(ReviewFeedback $f, ReviewFactor $teachersReview, UniqueID $studentFeedbacker) {
		$f->setTeachersComment($teachersReview);

		$this->saveReviewRFeedback($studentFeedbacker, $f);
	}

	public function newReview(Uniqueid $uid) : int {

		$index = $this->getReviewed($uid)->getCount();

		$ri = $this->getNewTestPlanReview($uid);

		//since this is randomly generated the review is saved as an empty review
		//This is done to mark this review as booked for later reviewing...
		//This might lead to some plans only getting empty reviews...
		$this->saveReview($ri, $index);

		return $index;
	}

	public function hasReviewed(Uniqueid $teacher, TestPlan $tp) {

		$allTeacherReviews = $this->getReviewed($teacher);

		foreach($allTeacherReviews  as $review) {
			if ($review->getTestPlan()->getMD5() == $tp->getMD5()) {
				return true;
			}
		}

		return false;
	}

	public function getReviewIndex(Uniqueid $teacher, TestPlan $tp) {


		$allTeacherReviews = $this->getReviewed($teacher);

		foreach($allTeacherReviews  as $review) {
			if ($review->getTestPlan()->getMD5() == $tp->getMD5()) {
				return $review->getIndex();
			}
		}

		throw new \Exception($this->language['exceptions']['user_has_not_reviewed']);
	}

	/**
	 * [teacherNewReview description]
	 * @param  Uniqueid $teacher [description]
	 * @param  TestPlan $tp      [description]
	 * @return returns the index of the review...
	 */
	public function teacherNewReviewAndReturnIndex(Uniqueid $teacher, TestPlan $tp) : int {
		//echo "Should create review on : " . $tp->getMD5();
		if ($this->hasReviewed($teacher, $tp))
			return $this->getReviewIndex($teacher, $tp);

		$ri = new TestPlanReview($tp, $teacher, new ReviewFactor("", new Grading()), new ReviewFactor("", new Grading()), new ReviewFactor("", new Grading()), new ReviewFactor("", new Grading()));

		$index = $this->getReviewed($teacher)->getCount();
		//echo "StudentModel: $index number of reviews by this teacher?";


		$this->saveReview($ri, $index);

		return $index;
	}

	public function getReviewed(Uniqueid $uid) : TestPlanReviewList {
		return $this->reviewDAL->getAllReviewsByUser($uid, $this->testPlanDAL);
	}

	public function getAllTestPlans() : TestPlanList {
		return $this->studentDAL->getAllActiveTestPlans($this->testPlanDAL);
	}

	public function getReviewableList(Uniqueid $uid) : TestPlanList{
		$tpl = $this->getAllTestPlans($this->studentDAL->getAllStudents());

		$tpl->remove($this->studentDAL->getTestPlanFromUser($uid, $this->testPlanDAL)); //remove the users TP

		$testPlansReviewed = $this->reviewDAL->getTestPlansReviewedByUser($uid, $this->testPlanDAL);
		$tpl->removeList($testPlansReviewed); //remove the ones already reviewed...

		$tpsw = $this->reviewDAL->getTPWithLeastReviews($tpl); //select all that has least reviews

		return $tpsw;
	}

	private function getNewTestPlanReview(Uniqueid $uid) : TestPlanReview {
		$tpsw = $this->getReviewableList($uid);

		$ri = new TestPlanReview($tpsw->getRandom(), $uid, new ReviewFactor("", new Grading()), new ReviewFactor("", new Grading()), new ReviewFactor("", new Grading()), new ReviewFactor("", new Grading()));

		return $ri;
	}

	public function getAllReviews(TestPlan $p) : TestPlanReviewList{
		return $this->reviewDAL->getAllReviewsForPlan($p, $this->testPlanDAL);
	}

	public function saveReviewRFeedback(Uniqueid $reviewer, ReviewFeedback $f) {
		$this->reviewFeedbackDAL->save($reviewer, $f);
	}

	public function getReviewFeedback(Uniqueid $reviewer, TestPlanReview $item) : ReviewFeedback{
		return $this->reviewFeedbackDAL->get( $reviewer, $item);
	}

	public function getReviewFeedbackList(TestPlanReview $item) : array {
		return $this->reviewFeedbackDAL->getReviewFeedbackList($item);
	}


	public function reviewHasFeedback(TestPlanReview $item) {
		return $this->reviewFeedbackDAL->reviewHasFeedback($item);
	}
	public function hasFeedbacked(Uniqueid $reviewer, TestPlanReview $item) : bool {
		return $this->reviewFeedbackDAL->hasFeedbacked($reviewer, $item);
	}


}
