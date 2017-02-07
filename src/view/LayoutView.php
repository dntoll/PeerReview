<?php

namespace view;

class LayoutView {


	private $sections = array();
	private $notices = array();
	private $headerText = "";
	private $headerComment = "";
	private $menu = "";

	public function getHTMLBody() {

		$ret = "
		<header id=\"header\">
			<h1>$this->headerText</h1>
			<p>$this->headerComment</p>
		</header>";

		$ret .= "$this->menu

		<div id=\"main\">";

		if (count($this->notices) > 0) {
			$ret .="<section id=\"content\" class=\"main\">		
				<section>";
			foreach($this->notices as $notice) {
				$ret .= "		
					$notice
				";
			}

			$ret .= "
				</section>
			</section>";
		}

		foreach($this->sections as $section) {
			$linkText = urlencode($section[0]);
			$content = $section[1];

			$ret .= "
			<a name=\"$linkText\"></a>
		<section id=\"content\" class=\"main\">		

			<section>
				 
				$content
			</section>
		</section>";
		}

		$ret .= "
	</div>";

		return $ret;
	}

	public function getSubMenu() {
		$ret = "<ul class='Submenu'>";

		foreach($this->sections as $section) {
			$headerLink = $section[0];
			$linkText = urlencode($section[0]);

			$ret .= "<li class='Submenu'><a href=\"#$linkText\">$headerLink</a></li>"; 
		}
		$ret .= "</ul>";
		return $ret;

	}

	public function addWarning(string $notice) {
		$this->notices[] =  "<div class='Warning'>" . $notice . "</div>";

	}
	public function addInformation(string $notice) {
		$this->notices[] =  "<div class='Information'>" . $notice . "</div>";

	}

	public function setHeaderText(string $text, string $comment) {
		$this->headerText = $text;
		$this->headerComment = $comment;
	}

	public function setMenu(string $menuText) {
		$this->menu = $menuText;
	}


	public function addSection(string $linkText, string $sectionText) {
		$this->sections[] = array($linkText, $sectionText);
	}
}