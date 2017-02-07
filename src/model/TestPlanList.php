<?php

namespace model;

class TestPlanList implements \IteratorAggregate {
	private $plans = array();

	public function remove(TestPlan $l) {
		unset($this->plans[$l->getMD5()]);
	}

	public function add(TestPlan $l) {
		$this->plans[$l->getMD5()] = $l;
	}



	public function removeList(TestPlanList $list) {
		foreach ($list->plans as $md5 => $toRemove) {
			$this->remove($toRemove, $md5);
		}
	}

	public function get() : array {
		return $this->plans;
	}
	

	public function getRandom() : TestPlan {
		$num = count($this->plans);

		if ($num == 0) {
			throw new \Exception("Failed to find a random testplan, none left");
		}
		$index = rand() % $num;

		foreach ($this->plans as $p) {
			$index--;
			if ($index <= 0) {
				return $p;
			}
		}

		throw new \Exception("Failed to find TestPlan");
	}

	public function getIterator() {
        return new \ArrayIterator($this->plans);
    }

    public function count() : int{
    	return count($this->plans);
    }
}