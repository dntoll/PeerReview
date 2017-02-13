<?php

namespace model;

class NotAFileException extends \Exception {}

class UploadedFile {
	private $tempName;

	public function __construct(array $fileArray) {
		$parameters = array("name", "tmp_name", "size", "type","error");

		foreach ($parameters as $param) {
			if (isset($fileArray[$param]) == FALSE) {
				throw new NotAFileException("Missing param $param");
			}
		}

		if ($fileArray["size"] <= 0) {
			throw new NotAFileException("The file had no content");
		}

		if ($fileArray["size"] >= 1000000) {
			throw new NotAFileException("The file had no content");
		}

		if ($fileArray["error"] !== UPLOAD_ERR_OK) {
			throw new NotAFileException("The upload failed");
		}


		/** Check what type of review the instance is set to**/
		if(REVIEW_SOURCE_TYPE==="md")
		{
			if (substr($fileArray["name"], strlen($fileArray["name"]) - 3) !== ".md" ) {
				throw new NotAFileException("The wrong type of file, only text files that ends with [\".md\"] allowed");
			}
		}
		elseif(REVIEW_SOURCE_TYPE ==="pdf") {
			if (substr($fileArray["name"], strlen($fileArray["name"]) - 4) !== ".pdf" ) {
				throw new NotAFileException("Filename:".$fileArray["name"]."The wrong type of file, only text files that ends with [\".pdf\"] allowed");
			}
		}
		else {
			throw new NotAFileException("Corrupt Settings file. You should not be here... Contact your teacher");
		}

		$this->tempName = $fileArray["tmp_name"];


	}

	function getMD5() : string{
		return md5_file($this->tempName);
	}

	function move(string $dest) {
		if (move_uploaded_file($this->tempName , $dest) == FALSE) {
			throw new \Exception("Unable to move file");
		}
	}
}
