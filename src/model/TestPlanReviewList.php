<?php

namespace model;

class TestPlanReviewList implements \IteratorAggregate {
	private $list = array();

	public function add(TestPlanReview $item, int $index) {
		$this->list[$index] =$item;
		ksort($this->list);
	}

	public function get($index) {
		return $this->list[$index];
	}

	/**
	 * not this may not be called isset, hence we renamed it to _isset
	 */
	public function _isset($index) {
		return isset($this->list[$index]);
	}

	public function getCount() {
		return count($this->list);
	}

	public function getFinishedCount() {
		$ret = 0;
		foreach ($this->list as $review) {
    		if ($review->isFinished() == true) {
    			$ret++;
    		}
    	}
    	return $ret;
	}

	public function getTextCount() {
		$ret = 0;
		foreach ($this->list as $review) {
    		if ($review->isFinished() == true) {
    			$ret += $review->getTextCount();
    		}
    	}
    	return $ret;
	}

	public function getIterator() {
        return new \ArrayIterator($this->list);
    }

    public function areFinished() {
    	foreach ($this->list as $review) {
    		if ($review->isFinished() == FALSE) {
    			return false;
    		}
    	}
    	return true;
    }

    
}