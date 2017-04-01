<?php

namespace model;


class UniqueIDException extends \Exception {}

class UniqueID {

	public function __construct(string $input) {
		$this->language = \Language::getLang();


		if (strlen($input) <= 3) {
			throw new UniqueIDException($this->language['exceptions']['uid_not_valid']);
		}

		if (strlen($input) >= 50) {
			throw new UniqueIDException($this->language['exceptions']['uid_not_valid']);
		}

		if (urlencode($input) !== $input) {
			throw new UniqueIDException($this->language['exceptions']['uid_not_valid']);
		}
		$regexp = "/[0-9a-zA-Z]*/";
		if (preg_match($regexp, $input) != 1) {

			throw new UniqueIDException($this->language['exceptions']['uid_not_valid']);
		}
		$this->uid = $input;
	}

	public function getName() {
		return $this->uid;
	}
}
