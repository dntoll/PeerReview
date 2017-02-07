<?php

namespace model;

class StudentDAL {
	private $settings;

	public function __construct(\Settings $settings) {

		$this->settings = $settings;
	}

	private function getFolder() {
		
		return $this->settings->getUploadPath() . DIRECTORY_SEPARATOR . "Users";
	}

	public function getAllStudents() : array {
		$ret = array();

		$filesAndFolders = scandir($this->getFolder());

		foreach($filesAndFolders as $fileOrFolder) {
			if ($fileOrFolder !== "." && $fileOrFolder !== "..") {
				$ret[$fileOrFolder] = file_get_contents($this->getFolder() .  DIRECTORY_SEPARATOR . $fileOrFolder);
			}
		}
		return $ret;
	}

	public function getAllActiveTestPlans(TestPlanDAL $dal) : TestPlanList {
		$students = $this->getAllStudents();

		$ret= new TestPlanList();

		foreach($students as $student => $testPlanmd5) {
			$ret->add($this->getTestPlanFromUser(new UniqueID($student), $dal));
		}

		return $ret;
	}


	public function hasPreviouslyUploadedTestPlan(UniqueID $student) : bool {
		return file_exists($this->getFolder() .  DIRECTORY_SEPARATOR . $student->getName());
	}

	public function getTestPlanFromUser(UniqueID $student, TestPlanDAL $dal) : TestPlan {
		return $dal->getTestPlan($this->getTestPlanMD5($student));
	}

	private function getTestPlanMD5(UniqueID $student) : string {
		if ($this->hasPreviouslyUploadedTestPlan($student) == FALSE && $this->settings->isTeacher($student) == false) {
			throw new \Exception("should never go to this point");
		}

		return file_get_contents($this->getFolder() .  DIRECTORY_SEPARATOR . $student->getName());
	}

	public function saveUserTestPlan(UploadedFile $file, UniqueID $student) {
		return file_put_contents($this->getFolder() .  DIRECTORY_SEPARATOR . $student->getName(), $file->getMD5());
	}

	
}