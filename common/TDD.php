<?php

namespace TDD;

class Assert {

	public static $lastExpected = NULL;

	public static function assertTrue(bool $actual, string $message = "") {
		if ($actual === FALSE) {
			self::formatMessage("false", "true", $message);
		}
	}

	public static function assertFalse(bool $actual, string $message = "") {
		if ($actual === TRUE) {
			self::formatMessage("true", "false", $message);
		}
	}

	public static function fail(string $message = "") {
		throw new AssertException("Assert fail [$message]");
	}

	public static function assertEquals($actual, $expected, string $message = "") {
		if ($actual != $expected) {
			self::formatMessage($actual, $expected, $message);
		}
	}


	private static function formatMessage(string $actual, string $expected, string $message = "") {
		if ($message != "") {
			throw new AssertException("Assert Failed [$message]");
		} else {
			throw new AssertException("Assert failed, was [$actual] but should be [$expected]. ");
		}
	}

	public static function expectException(\Exception $e) {
		self::$lastExpected = $e;
	}
}

class AssertException extends  \Exception {

}

abstract class SUTCreator {
	abstract function create();
}

class ReplaceByFunctionCreator extends SUTCreator {
	private $createFunction;
	
	public function setImplementation($f) {
		$this->createFunction = $f;
	}

	public function create() {
		return $this->createFunction->__invoke();
	}
}

function testSuiteOn(TestClass $testToEvaluate, $creatorImplementation, bool $expected, string $ok, string $failed) {
	$SUTCreator = new ReplaceByFunctionCreator();
	$testToEvaluate->setRunUnderTest($SUTCreator);

	$ts = new TestSuite();
	$ts->addTest($testToEvaluate);

	$SUTCreator->setImplementation( $creatorImplementation);
	if ($ts->runTests() != $expected) {
		throw new \Exception($failed);
	} else {

		echo "$ok\n";
	}
}

abstract class TestClass {
	private $runningMetaTest = false;

	public function setUp() {

	}

	public abstract function getSystemUnderTestInstance();

	public function tearDown() {
		
	}

	public function _getSystemUnderTestInstance() {
		if ($this->runningMetaTest === FALSE) {
			return $this->getSystemUnderTestInstance();
		} else {
			return $this->sutCreator->create();
		}
	}

	public function setRunUnderTest(SUTCreator $createSut) {
		$this->runningMetaTest = true;
		$this->sutCreator = $createSut;
	}
}


class TestSuite {
	private $testObjects = array();
	private $result = array();

	public function addTest(TestClass $toBeTested) {
		$this->testObjects[] = $toBeTested;
	}

	public function runTests() : bool{
		$allTestSucceeded = true;
		$this->result = array();
		Assert::$lastExpected = NULL;
		foreach($this->testObjects as $toBeTested) {
			$class_methods = \get_class_methods($toBeTested);

			foreach($class_methods as $method) {

				if ($method == "setUp" || 
					$method == "tearDown" || 
					$method == "getSystemUnderTestInstance" || 
					$method == "setRunUnderTest" || 
					$method == "_getSystemUnderTestInstance")
					continue;
				try {
					//Since methods are given as strings we need to invoce them like this
					$toBeTested->setUp();


					$sut = $toBeTested->_getSystemUnderTestInstance();
					$this->handleExpectedExceptions($toBeTested, $method, $sut);
					//only once
					Assert::$lastExpected = NULL;
					
					

					$toBeTested->tearDown();
					$this->result[] = new TestResult($toBeTested, $method, true, new \Exception());
				} catch (AssertException $e) {
					$this->result[] = new TestResult($toBeTested, $method, false, $e);
					$allTestSucceeded = false;
				} catch (\Exception $e) {
					$this->result[] = new TestResult($toBeTested, $method, false, $e);
					$allTestSucceeded = false;
				}
				Assert::$lastExpected = NULL;
			}
		}
		return $allTestSucceeded;
	}

	private function handleExpectedExceptions(TestClass $toBeTested, string $method, $sut) {
		try {
			call_user_func(array($toBeTested, $method), $sut );

			if (Assert::$lastExpected !== NULL) {
				throw new AssertException("Expected Exception " . get_class(Assert::$lastExpected) . " but got none");
			}

		} catch (AssertException $e) {
			throw $e;
		} catch (\Exception $e) {

			if (Assert::$lastExpected !== NULL) {
				if (get_class($e) === get_class(Assert::$lastExpected)) {
					//ok
				} else {
					throw new AssertException("Expected Exception of class " . get_class(Assert::$lastExpected) . " but catched " . get_class($e));
				}
			} else {
				throw $e;
			}
		}
	}

	public function showResults($showSucceeding = true) {
		echo "<h2>TestResult</h2>\n<table>";

		echo "<tr>"; 
			echo " <th>Class</th>\n";
			echo " <th>TestMethod</th>\n";
			echo " <th>Status</th>\n";
			echo " <th>Exception</th>\n";
			echo "</tr>";

		foreach($this->result as $subTest) {

			$successStyle = "background-color: green;";
			$successMessage = "Ok";
			$exceptionMessage = "";

			if ($subTest->didSucceed() == FALSE) {
				if ($subTest->capturedException()) {
					$exceptionMessage = "Exception:"  . $subTest->getMessage() ;
					$successStyle = "background-color: yellow;";
					$successMessage = "Exception";
				} else {
					$exceptionMessage =  $subTest->getMessage();
					$successStyle = "background-color: red;";
					$successMessage = "Failed";
				}
				
			} else {
				if ($showSucceeding == false) {
					continue;
				}
			}

			
			echo "<tr >\n"; 
			echo " <td>" . $subTest->getClassName(). "</td>\n";
			echo " <td>" . $subTest->getMethodName(). "</td>\n";
			echo " <td style='$successStyle'>$successMessage</td>\n";
			echo " <td> $exceptionMessage </td>\n";
			echo "</tr>\n";
		}
		echo "</table>";
	}
}

class TestResult {
	private $className;
	private $methodName;

	public function __construct(TestClass $object, string $methodName, bool $didSucceed, \Exception $failure) {
		$this->className = get_class($object);
		$this->methodName = $methodName;
		$this->didSucceed = $didSucceed;
		$this->exception = $failure;
	}

	public function getClassName() : string {
		return $this->className;
	}

	public function getMethodName() : string {
		return $this->methodName;
	}

	public function didSucceed() : bool {
		return $this->didSucceed;
	}

	public function getMessage() : string {
		return $this->exception->getMessage();
	}

	public function capturedException() : bool {
		return get_class($this->exception) !== "TDD\AssertException";
		
	}
}