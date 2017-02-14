<?php

namespace model;

class TestPlanDAL {

	private $settings;

	public function __construct(\Settings $settings) {

		$this->settings = $settings;
	}

	public function doSaveTestPlan(UploadedFile $file) {
		$dest = $this->getTestPlanFileName($file).".".REVIEW_SOURCE_TYPE;

		if (file_exists( $this->getFolder() ) == false) {
			mkdir( $this->getFolder());
		}

		$file->move($dest);
	}

	public function getTestPlan(string $md5) {
		return new TestPlan($this->getFolder() . DIRECTORY_SEPARATOR . $md5, $md5);
	}


	private function getFolder() {

		return $this->settings->getUploadPath() . DIRECTORY_SEPARATOR . "TestPlan";
	}

	private function getTestPlanFileName(UploadedFile $file) {
		return $this->getFolder() . DIRECTORY_SEPARATOR . $file->getMD5();
	}

	public function getAllUploadedTestPlans() : TestPlanList {
		$tpl = new TestPlanList();

		$filesAndFolders = scandir($this->getFolder());



		foreach($filesAndFolders as $fileOrFolder) {
			if ($fileOrFolder !== "." && $fileOrFolder !== "..") {
				$testPlan = new TestPlan($this->getFolder() . DIRECTORY_SEPARATOR . $fileOrFolder, $fileOrFolder);

				$tpl->add($testPlan, $fileOrFolder);
			}
		}


		return $tpl;
	}
}
