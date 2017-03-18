<?php

namespace model;

class TestPlan {
	private $filePath;
	private $md5;


	public function __construct(string $filePath, string $md5) {
		$this->filePath = $filePath;
		$this->md5 = $md5;
	}

	public function getName() : string {
		return basename($this->filePath);
	}

	public function getContent() :string {
		include("./language.php");

		$filePathLocal = UPLOAD_PATH . "/TestPlan/" . $this->getName();
		$fileContent = file_get_contents($filePathLocal);

		if (file_exists($filePathLocal)) {

			$fileContent = htmlspecialchars($fileContent, ENT_HTML5 | ENT_NOQUOTES | ENT_SUBSTITUTE);

			return $fileContent;
		}
		else
			return $lang[LANGUAGE]['document']['no_md_file'];
	}

	public function getPdf() : string {

				$filePathLocal = UPLOAD_PATH ."/TestPlan/" . $this->getName();
				//$fileContent = readfile($filePathLocal);
				$fw = "/data/2dv610/TestPlan/" . $this->getName();
				if (file_exists($filePathLocal)) {
					return $fw;
				}
				else
					return $lang[LANGUAGE]['document']['no_pdf_file'];
				}

	public function getMD5() {
		return $this->md5;
	}




}
