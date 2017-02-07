<?php

namespace model;

class AFGrader {


	public function getScore(TestPlanReviewList $reviews) : Grading {
		$scores = array();
		foreach($reviews as $review) {

            if ($review->isFinished() == true) {
    			if ($review->getTeacherFeedback()->getGrading()->getValue() != Grading::FAILED) {
    				$scores[] = $review->getClarity()->getGrading()->getValue();
    				$scores[] = $review->getCompleteness()->getGrading()->getValue();
    				$scores[] = $review->getContent()->getGrading()->getValue();
    			}
            }
		}
	

		if (count($scores) > 0) {
			return new Grading($this->getMedian($scores));
		} else {

			return new Grading(Grading::NOT_GRADED);	
		}
	}



	public function getReviewScore(TestPlanReviewList $reviewsMade, StudentModel $m) : string {
    	$highestReviewScores = $this->getHighestRatedReviewFeedbackForAllReviewsMade($reviewsMade, $m);


    	//A or B
    	if (count($highestReviewScores) >= 4) {
    		if ($highestReviewScores[0] == Grading::EXCELLENT &&
    			$highestReviewScores[1] == Grading::EXCELLENT &&
    			$highestReviewScores[2] >= Grading::GOOD && 
    			$highestReviewScores[3] >= Grading::GOOD) {
    			return "A";
    		}
    		if ($highestReviewScores[0] == Grading::EXCELLENT &&
    			$highestReviewScores[1] >= Grading::GOOD &&
    			$highestReviewScores[2] >= Grading::GOOD &&
    			$highestReviewScores[3] >= Grading::SUFFICIENT) {
    			return "B";
    		}
    	}
    	//C or D
    	if (count($highestReviewScores) >= 3) {
    		if ($highestReviewScores[0] >= Grading::GOOD &&
    			$highestReviewScores[1] >= Grading::GOOD &&
    			$highestReviewScores[2] >= Grading::GOOD) {
    			return "C";
    		}
    		if ($highestReviewScores[0] >= Grading::GOOD &&
    			$highestReviewScores[1] >= Grading::SUFFICIENT &&
    			$highestReviewScores[2] >= Grading::SUFFICIENT) {
    			return "D";
    		}
    	}
    	//E
    	if (count($highestReviewScores) >= 2) {
    		if ($highestReviewScores[0] >= Grading::SUFFICIENT &&
    			$highestReviewScores[1] >= Grading::SUFFICIENT) {
    			return "E";
    		}
    	}

    	return "F";
    }

    public function getGradeFromScore(Grading $score) {
    	switch ($score->getValue()) {
    		case Grading::EXCELLENT : return "A";
    		case Grading::GOOD : return "C";
    		case Grading::SUFFICIENT : return "E";
    	}

    	return "F";

    }


    private function getMedian(array $scores) : int {
		sort($scores);
		$num = count($scores);
		$middleIndex = intval($num / 2);
		$hasEvenNumbers = $num % 2 == 0;

		if ($hasEvenNumbers) {
			$sumOfMiddleValues = $scores[$middleIndex-1] + $scores[$middleIndex ];
			return $sumOfMiddleValues/2;
		} else {
			return $scores[$middleIndex];
		}
		
	}


    private function getHighestRatedReviewFeedbackForAllReviewsMade(TestPlanReviewList $reviewsMade, StudentModel $m) : array {
        $highestReviewScores = array();
        foreach($reviewsMade as $review) {

            //Must be completed
            if ($review->isFinished() == true) {

                //IF it is failed by the teacher it should not be included
                if ($review->getTeacherFeedback()->getGrading()->getValue() !== Grading::FAILED) {


                    //Adding the teachers score to the highest
                    //This should instead replace the score given since the 
                    //quantity-grading becomes faulty
                    if ($review->getTeacherFeedback()->getGrading()->getValue() > Grading::NOT_GRADED) {
                        $allValuesForThisReview[] = $review->getTeacherFeedback()->getGrading()->getValue();
                    }

                    if ($m->reviewHasFeedback($review)) {
                        $feedbacks = $m->getReviewFeedbackList($review);

                        //Get the highest value for this feed
                        $allValuesForThisReview = array();
                        foreach($feedbacks as $feed) {
                            if ($feed->getTeacherFeedback()->getGrading()->getValue() !== Grading::FAILED) {
                                $allValuesForThisReview[] = $feed->getGrading()->getValue();
                            }
                        }
                        
                        //we could really add all?
                        if (count($allValuesForThisReview) > 0) {
                            rsort($allValuesForThisReview);
                            $highestReviewScores[] = $allValuesForThisReview[0];
                        }
                    }
                    
                }
                
            }
        }
        //The highest grades ends up in the top of the array
        rsort($highestReviewScores);
        return $highestReviewScores;
    }
}