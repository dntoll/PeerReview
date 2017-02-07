<?php

namespace view;


class GradingView {
	private $index;

	/**
	 * Since many of these may be present on the same form, they are separated by postindicies
	 * @param string $postIndex [description]
	 */
	public function __construct(string $postIndex) {
		$this->index = $postIndex;
	}

	public function getPostedGrading() : \model\Grading {
		return new \model\Grading(intval($_POST[$this->index]));
	}

	public function getForm(\model\Grading $grade, array $alternativeNames) {

		$score = $grade->getValue();

		$ret = "";
		foreach ($alternativeNames as $key => $value) {
			$ret .= "<input type='radio' name='$this->index' id='$key$this->index' value='$key' " . ($score == $key ? "checked" : "") . "><label for='$key$this->index'>" . $value . "</label><br/>";
		}
		return $ret;
	}

	public function getGradeHTML(\model\Grading $grade, array $alternativeNames) {
		$score = $grade->getValue();		

		return $alternativeNames[$score];
		
	}
}