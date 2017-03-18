<?php

namespace model;

class NotAFileException extends \Exception {}

class UploadedFile {
	private $tempName;

	public function __construct(array $fileArray) {
		include("./language.php");
		$parameters = array("name", "tmp_name", "size", "type","error");

		foreach ($parameters as $param) {
			if (isset($fileArray[$param]) == FALSE) {
				throw new NotAFileException($lang[LANGUAGE]['exceptions']['missing_param']." $param");
			}
		}

		if ($fileArray["size"] <= 0) {
			throw new NotAFileException($lang[LANGUAGE]['exceptions']['file_no_content']);
		}

		if ($fileArray["size"] >= 1000000) {
			throw new NotAFileException($lang[LANGUAGE]['exceptions']['file_no_content']);
		}

		if ($fileArray["error"] !== UPLOAD_ERR_OK) {
			throw new NotAFileException($lang[LANGUAGE]['exceptions']['upload_failed']);
		}


		/** Check what type of review the instance is set to**/
		if(REVIEW_SOURCE_TYPE==="md")
		{
			if (substr($fileArray["name"], strlen($fileArray["name"]) - 3) !== ".md" ) {
				throw new NotAFileException($lang[LANGUAGE]['exceptions']['md_wrong_type_file']);
			}
		}
		elseif(REVIEW_SOURCE_TYPE ==="pdf") {
			if (substr($fileArray["name"], strlen($fileArray["name"]) - 4) !== ".pdf" ) {
				throw new NotAFileException($lang[LANGUAGE]['exceptions']['pdf_wrong_type_file']);
			}
		}
		else {
			throw new NotAFileException($lang[LANGUAGE]['exceptions']['corrupt_settings']);
		}
		$this->tempName = $fileArray["tmp_name"];
	}

	function getMD5() : string{
		return md5_file($this->tempName);
	}

	function move(string $dest) {
		include("./language.php");
		if (move_uploaded_file($this->tempName , $dest) == FALSE) {
			throw new \Exception($lang[LANGUAGE]['exceptions']['unable_to_move_file']);
		}
	}
}
