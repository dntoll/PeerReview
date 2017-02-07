<?php

namespace model;


class UniqueIDException extends \Exception {}

class UniqueID {

	public function __construct(string $input) {
		if (strlen($input) <= 3) {
			throw new UniqueIDException("Not a valid uid 1");
		}

		if (strlen($input) >= 50) {
			throw new UniqueIDException("Not a valid uid");
		}

		if (urlencode($input) !== $input) {
			throw new UniqueIDException("Not a valid uid");
		}
		$regexp = "/[0-9a-zA-Z]*/";
		if (preg_match($regexp, $input) != 1) {
			
			throw new UniqueIDException("not valid UniqueID");
		}
		$this->uid = $input;
	}

	public function getName() {
		return $this->uid;
	}
}